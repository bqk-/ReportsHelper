<?php
/**
 *
 * @package scf-framework
 * @author Steve
 * @date 12/17/13
 * @version 5.2
 */

class wfc_core_class {
    public function getSitesList( &$analytics ){
        if( isset($_GET['refresh']) || empty($_SESSION['list_sites']) ){
            $result   = array();
            $accounts = $analytics->management_accounts->listManagementAccounts();
            if( count( $accounts->getItems() ) > 0 ){
                $items = $accounts->getItems();
                foreach( $items as $a ){
                    $webproperties = $analytics->management_webproperties->listManagementWebproperties( $a->getId() );
                    if( count( $webproperties->getItems() ) > 0 ){
                        $items_p = $webproperties->getItems();
                        foreach( $items_p as $b ){
                            $profiles = $analytics->management_profiles->listManagementProfiles( $a->getId(), $b->getId() );
                            if( count( $profiles->getItems() ) > 0 ){
                                $items_s = $profiles->getItems();
                                foreach( $items_s as $c ){
                                    $result[] = $c;
                                }
                            }
                        }
                    }
                }
            }
            $_SESSION['list_sites'] = serialize( $result );
        } else{
            $result = unserialize( $_SESSION['list_sites'] );
        }
        return $result;
    }

    public function scf_get_property_domain( $id, $ua_code, &$analytics ){
        $profiles = $analytics->management_profiles->listManagementProfiles( $id, $ua_code );
        $items_s  = $profiles->getItems();
        //echo $items_s[0]->getWebsiteUrl();
        //print_r( $items_s[0] );
    }
}