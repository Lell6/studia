<?php
namespace App\controller;

use App\repository\LectureInstrumentRepository;
use App\service\AccessHandler;
use App\service\PagesHandler;

class LectureInstrumentController {
    private $accessHandler;
    private $pagesHandler;

    private $lectureInstrumentRepository;

    public function __construct()
    {
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->lectureInstrumentRepository = new LectureInstrumentRepository();
    }

    public function addLectureInstruments($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureId = (null !== $request->getAttribute('lectureId')) ? $request->getAttribute('lectureId') : null;
        if ($lectureId == null) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Nie podano Id wykładu"]);
        }
        
        $body = $request->getParsedBody();
        $instrumentId = $body['instrumentId'];

        $result = $this->lectureInstrumentRepository->addInstrumentToLecture($lectureId, $instrumentId);
        if (!$result['success']) {
            return $this->pagesHandler->loadSelectedLecturePage($request, $response, ['id' => $lectureId], [$result['message']]);
        }

        return $response->withHeader('Location', '/lecture/'.$lectureId)->withStatus(302);
    }

    public function deleteLectureInstrument($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureId = (null !== $request->getAttribute('lectureId')) ? $request->getAttribute('lectureId') : null;
        $instrumentId = (null !== $request->getAttribute('instrumentId')) ? $request->getAttribute('instrumentId') : null;
        if ($lectureId == null || $instrumentId == null) {
            return $this->pagesHandler->loadLecturesListPage($request, $response, null, ["Nie podano Id wykładu lub Id sprzętu"]);
        }

        $result = $this->lectureInstrumentRepository->deleteInstrumentInLecture($lectureId, $instrumentId);
        if (!$result['success']) {
           return $this->pagesHandler->loadSelectedLecturePage($request, $response, ['id' => $lectureId], [$result['message']]);
        }

        return $response->withHeader('Location', '/lecture/'.$lectureId)->withStatus(302);
    }
}