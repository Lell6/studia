<?php
namespace App\service;

class Template {    
    public function setTemplate($pathToTemplate, $pathToStyleTemplate = null, $styleData = null){
        $twig = new \Twig\Environment(new \Twig\Loader\ArrayLoader());

        $templateContent = file_get_contents($pathToTemplate);
        $template = $twig->createTemplate($templateContent);

        if (empty($pathToStyleTemplate)) {
            return $template;
        }
        
        $styleTemplateContent = file_get_contents($pathToStyleTemplate);
        $styleTemplate = $twig->createTemplate($styleTemplateContent);
        $renderStyle = $styleTemplate->render($styleData);

        return [
            'pageTemplate' => $template,
            'styleTemplate' => $renderStyle
        ];
    }
}