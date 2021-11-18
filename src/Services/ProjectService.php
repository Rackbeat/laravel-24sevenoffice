<?php

namespace KgBot\SO24\Services;

class ProjectService extends BaseService
{
    protected $serviceUrl = 'https://webservices.24sevenoffice.com/Project/V001/ProjectService.asmx?wsdl';

    protected function getIndexMethod(): string {
        return 'GetProjectList';
    }

    protected function getIndexReturnName() {
        return [];
    }

    protected function getIndexSearchName() {
        return "ProjectSearch";
    }

    public function getNameList() {
        return $this->request->call('GetProjectNameList', [])->getResults();
    }
}
