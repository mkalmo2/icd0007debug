<?php

namespace stf;

use stf\browser\Browser;
use stf\browser\HttpBrowser;
use stf\browser\Url;
use stf\browser\WebDriverBrowser;

class Globals {

    const MAX_WAIT_TIME = 2;
    const POLL_FREQUENCY = 200;
    const SELENIUM_SERVER_URL = 'http://localhost:4444/';

    public Url $baseUrl;

    public bool $logRequests = false;
    public bool $logPostParameters = false;
    public bool $printStackTrace = false;
    public bool $printPageSourceOnError = false;
    public bool $leaveBrowserOpen = false;
    public bool $showBrowser = false;

    private bool $useWebDriver = false;
    private ?Browser $browser;

    public function setUseWebDriver(bool $shouldUse) {
        $this->useWebDriver = $shouldUse;
        $this->browser = null;
    }

    public function getBrowser() {
        if (isset($this->browser)) {
            return $this->browser;
        }

        $this->browser = $this->useWebDriver
            ? new WebDriverBrowser($this)
            : new HttpBrowser($this, $this->baseUrl ?? new Url(''));

        return $this->browser;
    }
}

