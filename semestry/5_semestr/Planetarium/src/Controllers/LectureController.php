<?php
namespace App\controller;

use App\repository\LectureInstrumentRepository;
use App\repository\LectureRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;

class LectureController {
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    private $lectureRepository;

    public function __construct()
    {
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->lectureRepository = new LectureRepository();
    }

    public function downloadSelectedLectureContent($request, $response) {     
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
           
        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        if ($id == null) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Nie podano Id wykładu"]);
        } 

        $lecture = $this->lectureRepository->getLectureById($id);
        if (!$lecture) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ['Wykład z takim ID nie istnieje']);
        }

        $result = $this->lectureRepository->getFilePath($id);
        $filePath = $result['Treść_Wykładu'];

        if (file_exists($filePath)) {
            $fileName = basename($filePath);
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Expires: 0');
            header('Content-Length: ' . filesize($filePath));
            
            ob_clean();
            flush();
            
            readfile($filePath);
            return $response->withHeader('Location', '/lecture/'.$id)->withStatus(302);
        } else {
            return $this->pagesHandler->loadSelectedLecturePage($request, $response, null, ["Błąd pobierania pliku"]);
        }
    }

    public function saveFileToSystem($lectureId, $fileContent) {
        $uploadedFile = $fileContent;
        $tmpFilePath = $uploadedFile['tmp_name'];
        $originalFileName = basename($uploadedFile['name']);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        $targetDir = __DIR__ . '\\..\\lecturesContent\\';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $newFileName = 'wykład_' . $lectureId . '.' . $fileExtension;
        $newFilePath = $targetDir . $newFileName;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            $this->lectureRepository->updateFilePath($lectureId, $newFilePath);
            return true;
        } else {
            return false;
        }
    }

    public function addNewLecture($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'lecture');

        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $inputValues['date'] = $inputValues['date'] . " " . $inputValues['time'];
        unset($inputValues['time']);

        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadCreateNewLecturePage($request, $response, null, $errorMessages);
        }

        $result = $this->lectureRepository->addNewLecture($inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadCreateNewLecturePage($request, $response, null, [$result['message']]);
        }

        if (!empty($inputValues['content']) && !$this->saveFileToSystem($this->lectureRepository->getInsertedLectureId(), $inputValues['content'])) {
            return $this->pagesHandler->loadCreateNewLecturePage($request, $response, null, ["Błąd podczas przesyłania pliku."]);
        }

        return $response->withHeader('Location', '/lectures')->withStatus(302);
    }

    public function changeSelectedLectureData($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Nie podano wykładu do zmiany"]);
        } 

        $body = $request->getParsedBody();
        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'lecture');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);

        $inputValues['date'] = $inputValues['date'] . " " . $inputValues['time'];
        unset($inputValues['time']);

        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdateSelectedLecturePage($request, $response, null, $errorMessages);
        }

        $result = $this->lectureRepository->updateLectureById($id, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadUpdateSelectedLecturePage($request, $response, null, [$result['message']]);
        }

        if (!empty($inputValues['content']) && !$this->saveFileToSystem($id, $inputValues['content'])) {
            return $this->pagesHandler->loadUpdateSelectedLecturePage($request, $response, null, ["Błąd podczas przesyłania pliku."]);
        }

        return $response->withHeader('Location', '/lectures')->withStatus(302);
    }

    public function deleteSelectedLecture($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Nie podano wykładu do zmiany"]);
        } 

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, $errorMessages);
        }

        $result = $this->lectureRepository->getFilePath($id);
        $filePath = $result['Treść_Wykładu'] ?? "";
        
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Błąd usuwania pliku"]);
            }
        }

        $lectureInstrumentRepo = new LectureInstrumentRepository();
        if (!$lectureInstrumentRepo->deleteAllInstrumentInLecture($id)) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Sprzęt nie został usunięty"]);
        }

        $result = $this->lectureRepository->deleteLectureById($id);
        if (!$result['success']) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/lectures')->withStatus(302);
    }
}