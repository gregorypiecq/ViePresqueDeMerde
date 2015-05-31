<?php
use Behat\WebApiExtension\Context\WebApiContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
/**
 * Defines application features from the specific context.
 */
class PostsContext extends WebApiContext
{
    /**
     * @When je vais sur la page d'accueil
     */
    public function jeVaisSurLaPageDAccueil()
    {
        throw new PendingException();
    }

    /**
     * @Then Then response code should be :arg1
     */
    public function thenResponseCodeShouldBe($arg1)
    {
        throw new PendingException();
    }
    /**
     * @When I send a GET request to :arg1
     */
    public function iSendAGetRequestTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then the response should contain json:
     */
    public function theResponseShouldContainJson(PyStringNode $string)
    {
        throw new PendingException();
    }
    
    /**
     * @Then the response json's :arg1 key should be of type :arg2
     */
    public function theResponseJsonSKeyShouldBeOfType($arg1, $arg2)
    {
        throw new PendingException();
    }
}
