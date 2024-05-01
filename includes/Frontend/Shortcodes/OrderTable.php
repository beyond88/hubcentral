<?php

namespace HubCentral\Frontend\Shortcodes;

/**
 * The admin class
 */
class OrderTable
{

    /**
     * Private attributes
     * 
     * @var string
     */
    private $atts;

    /**
     * Initialize the class
     * 
     * @since   1.0.0
     * @access  public
     * @param   none
     * @return  void
     */
    function __construct()
    {
        add_shortcode('hub_order_list', array($this, 'hub_order_list'));
    }

    /**
     * Shortcode callback method
     *
     * @since   1.0.0
     * @access  public
     * @param   array
     * @return  string
     */
    public function hub_order_list($atts)
    {
        $this->atts = shortcode_atts(array(), $atts);

        return $this->output();
    }

    /**
     * Render samply button
     *
     * @since    1.0.0
     * @access  public
     * @param    none
     * @return   string
     */
    public function output()
    {
        ob_start();
        echo "I am from shortcode!";
        return ob_get_clean();
    }
}
