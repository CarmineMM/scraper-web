# Web Scraping

Este Scraper busca realiza descargas de recursos (imágenes) de sitios webs, según sea la configuración de los Drives.

### Requerimientos

- PHP 7.4.16

Se require tener habilitado PHP en la __CLI__ y la version minima debe ser la __7.4.16__
```
$ php --version
```

### Configurar

La configuración del paquete ocurre en el archivo __config.php__

#### Configuración: _drive_

```php
/**
* -----------------------------------
*  Drive            
* ----------------------------------- 
* 
* ...
* 
*/
'drive' => 'tmo',
```
El _drive_ indica condicionalmente al scraper de donde se tomaran los recursos, los drive soportados son:

<table>
    <thead>
        <tr>
            <th>Drive</th>
            <th>Referencia</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>tmo</td>
            <td>https://lectortmo.com/</td>
        </tr>
    </tbody>
</table>
<br />

#### Configuración: _url_

```php
/**
* -----------------------------------
* URL completa del Manga
* -----------------------------------
* 
* ...
* 
*/
'url' => '',
```
Especifica la URL del manga, ej: https://lectortmo.com/library/manga/8635/mushoku-tensei-isekai-ittara-honki-dasu
la URL debe concordar con el _drive_ especificado.

> Nota: También se puede colocar el Link de un capitulo en especifico, y el Scraper descargara las imágenes del recurso especifico. ej: https://lectortmo.com/viewer/5af24a054e682/paginated


#### Configuración: _start_ - _end_

Especifica el numero de inicio y fin para la descarga de recurso, este numero no representa el numero especifico del Capitulo (recurso), si no que es el numero en __conteo__ por donde empieza y termine, ej:
- Número: 01 - Capitulo: 01.0
- Número: 02 - Capitulo: 02.0
- Número: 03 - Capitulo: 02.3
- Número: 04 - Capitulo: 02.6
- Número: 05 - Capitulo: 03.0

```php
/**
 * -----------------------------------
 * Numero de inicio y fin
 * -----------------------------------
 * 
 * ...
 * 
 */
'start' => 0,     
'end'   => false, # 'false' indica que descargara todo lo disponible
```


## Guardado de archivos

Los archivos se guardan en la carpeta __"./manga"__ y luego el nombre del recurso descargado.


## Ejecutar

Para ejecutar el Scraper una vez finalizada la configuración, ejecute en la carpeta raíz:

```
$ php run
```
Esto iniciara el Scraper, y si esta configurado, iniciara las descargas satisfactoriamente.

## Licencia 

Copyright © 2021 Carmine Maggio

Licenciado bajo la licencia MIT, ve [LICENSE.md](LICENSE.md) para más detalles.