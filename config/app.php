<?php
/**
 * ============================================================================
 * @Author Suresh Bhatt 
 * @Year 2018
 * =============================================================================
 */

return [


    /* app name*/
    
    
    
    'app'=>env('APP_NAME'),
    
    
    
    /* Base url*/
    
    
    'url'=> env('APP_URL'),
    
    
    
    /* local Time zone */
    
    
    'locale'=>env('TIME_ZONE'),
    
    /* App key */
    
    'key'=>env('APP_KEY'),

  /* App Host */

    'host' => env('DB_HOST'),


    
    'db' => env('DB_NAME'),


    'user' => env('DB_USER'),


    'pass' => env('DB_PASS'),


    'driver' => env('DB_DRIVER'),



    'db_prefix' => env('DB_PREFIX'),
	
	
	
    'cache' => env('VIEW_CACHE'),


    'from_mail' => env('FROM_MAIL'),


    'admin_mail' => env('ADMIN_MAIL'),


    'is_debugg' => env('IS_DEBUGG'),
    
    
    
];
    