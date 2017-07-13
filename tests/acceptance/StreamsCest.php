<?php

class StreamsCest
{
    public function streamsRouteWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/v1/streams');
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        // @todo in the future, don't hard code these, check them against the known schema
        $I->seeResponseJsonMatchesJsonPath('$[0].id');
        $I->seeResponseJsonMatchesJsonPath('$[0].streamUrl');
        $I->seeResponseJsonMatchesJsonPath('$[0].captions.vtt.en');
        $I->seeResponseJsonMatchesJsonPath('$[0].captions.scc.en');
        $I->seeResponseJsonMatchesJsonPath('$[0].ads');
        $I->seeResponseJsonMatchesJsonPath('$[1].id');
        $I->seeResponseJsonMatchesJsonPath('$[1].streamUrl');
        $I->seeResponseJsonMatchesJsonPath('$[1].captions.vtt.en');
        $I->seeResponseJsonMatchesJsonPath('$[1].captions.scc.en');
        $I->seeResponseJsonMatchesJsonPath('$[1].ads');
        $I->seeResponseJsonMatchesJsonPath('$[2].id');
        $I->seeResponseJsonMatchesJsonPath('$[2].streamUrl');
        $I->seeResponseJsonMatchesJsonPath('$[2].captions.vtt.en');
        $I->seeResponseJsonMatchesJsonPath('$[2].captions.scc.en');
        $I->seeResponseJsonMatchesJsonPath('$[2].ads');
    }
}
