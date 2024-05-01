<?php

namespace HubCentral;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     * 
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    function __construct() {
        new Admin\Main();
    }

    /**
     * Dispatch and bind actions
     *
     * @since   1.0.0
     * @access  public
     * @param   string
     * @return  void
     */
    public function dispatch_actions( $main ) {

    }
}