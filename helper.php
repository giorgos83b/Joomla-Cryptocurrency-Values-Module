<?php
defined('_JEXEC') or die();
/**
 * Helper class for Crypto Values! module
 *
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_cryptovalues is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModCryptoValuesHelper
{
    /**
     * Retrieves the top ten cryptos as they are listed on the CMC website
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */
    public static function getCoinValues($params)
    {

      $coin_values_and_more = array();

      $convert_rate = '&convert='.$params->get('currency_rate','USD');
      $limit_currencies = intval($params->get('no_of_cryptos','10'));

		$data = "{data:{}}";
      //$data = file_get_contents('https://api.coinmarketcap.com/v2/ticker/?limit='.$limit_currencies.$convert_rate);

      $coin_data = json_decode($data, true);

      $coin_array = $coin_data['data'];

      return $coin_array;
    }

    public static function getUserOptions($params)
    {



      $icon_size_option = $params->get('icon_size', 1);

      $icon_size_h = array(

        0=> false,
        1=> '16x16',
        2=> '32x32',
        3=> '64x64'

      );

      $user_options['icon_size'] = $icon_size_h[$icon_size_option];
      $user_options['currency_rate'] = $params->get('currency_rate','USD');
      $user_options['display_link'] = intval($params->get('friendly_link'));
      $user_options['live_updates'] = intval($params->get('live_updates'));
      $user_options['crypto_pairs'] = $params->get('crypto_pairs', 'BTC-USD');
      $user_options['currency_direction'] = intval($params->get('currency_direction'));
      return $user_options;
    }
}
