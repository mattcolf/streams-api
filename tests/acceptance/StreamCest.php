<?php

class StreamCest
{
    public function streamsRouteWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/v1/streams/5938b99cb6906eb1fbaf1f1c');
        $I->seeResponseCodeIs(200);
        $I->seeHttpHeader('Content-Type', 'application/json');
        $I->seeResponseIsJson();

        // @todo in the future, don't hard code these, check them against the known schema
        $I->seeResponseJsonMatchesJsonPath('$.id');
        $I->seeResponseJsonMatchesJsonPath('$.streamUrl');
        $I->seeResponseJsonMatchesJsonPath('$.captions.vtt.en');
        $I->seeResponseJsonMatchesJsonPath('$.captions.scc.en');
        $I->seeResponseJsonMatchesJsonPath('$.ads');
    }

    public function streamsRouteNotFound(AcceptanceTester $I)
    {
        $I->amOnPage('/v1/streams/abc123');
        $I->seeResponseCodeIs(404);
    }
}
