<?php 

/**
 * Scraper para descargar contenido.
 * Version: 1.0.0
 * 
 * Version Minima de PHP: 7.2
 * 
 * Carmine Maggio <carminemaggiom@gmail.com>
 * 
 * The user who executes this program is solely responsible for the misleading distribution and misuse of resources. 
 * Carmine Maggio disclaims any moral responsibility and copyright infringement. 
 */
return [

    /**
     * -----------------------------------
     *  Drive            
     * ----------------------------------- 
     * 
     * Drive que se usara para el Scraping,
     * también representa la pagina web con el contenido,
     * las opciones disponibles y su web son:
     * - tmo = https://lectortmo.com/
     * 
     */
    'drive' => 'tmo',

    /**
     * -----------------------------------
     * URL completa del Manga
     * -----------------------------------
     * 
     * URL y Path del recurso a descargar, 
     * esto debe concordar con el 'drive' configurado.
     * Ej: https://lectortmo.com/library/manga/8635/mushoku-tensei-isekai-ittara-honki-dasu
     * 
     */
    'url' => '',

    /**
     * -----------------------------------
     * Numero de inicio y fin
     * -----------------------------------
     * 
     * Indica el inicio y el fin de la descarga,
     * ambos son el numero exacto según el conteo de recursos.
     * No se toman en cuenta el numero del capitulo que tengan.
     * Ej: Numero - Numero del capitulo
     *         01 - 01.0
     *         02 - 02.0
     *         03 - 02.3
     *         04 - 02.6
     *         05 - 03.0
     * 
     */
    'start' => 0,     
    'end'   => false, # 'false' indica que descargara todo lo disponible
];