<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 13-Jun-19
 * Time: 4:20 PM
 */

namespace Framework;


class HtaccessGenerator {
    public function write($path){
        if(!$path){
            $path = ROOT_PATH . '/.htaccess';
        }
        file_put_contents($path, self::render('public', null));
    }

    public function render($public_dirname, $subdir){
        $public_dirname = trim($public_dirname, '\/');
        $subdir = trim($subdir, '\/');
        if($subdir){
            $public_dirname = "$subdir/$public_dirname";
        }
        $template =
<<<MULTILINE
RewriteEngine On
# If the URL starts with public/ or public$ then this is the last rule, apache will handle the rest.
# TODO: I should add an exception to .php files.
RewriteRule ^($public_dirname)($|/) - [L]

# For all other files first check if they exist in 'public'
RewriteCond %{DOCUMENT_ROOT}/$public_dirname%{REQUEST_URI} -f
RewriteRule ^ $public_dirname%{REQUEST_URI} [L]

RewriteRule ^(.+)$ index.php [QSA,L,E=UNDER_REWRITE=YES]

# for debug purpose from php script, you may use this:
# RewriteRule ^(.+)$ index.php [QSA,L,E=REQUEST_FILENAME:%{REQUEST_FILENAME},E=REQUEST_URI:%{REQUEST_URI},E=DOCUMENT_ROOT:%{DOCUMENT_ROOT},E=DOCUMENT_X:%{DOCUMENT_ROOT}public%{REQUEST_URI}]

MULTILINE;
    return $template;
    }
}
