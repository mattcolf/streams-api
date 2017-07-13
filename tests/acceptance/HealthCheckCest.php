<?php

class HealthCheckCest
{
    public function healthcheckWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
        $I->see('good');
    }
}
