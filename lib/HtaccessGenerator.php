<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 13-Jun-19
 * Time: 4:20 PM
 */

namespace Framework;


class HtaccessGenerator {
    public function write($path=null, $subdir=null, $public_dirname=null){
        if(!$path){
            $path = ROOT_PATH . '/.htaccess';
        }
        $text = self::render($subdir, $public_dirname);
        file_put_contents($path, self::render($subdir, $public_dirname));
        return $text;
    }

    public function render($subdir, $public_dirname){
        if(!$public_dirname){
            $public_dirname = 'public';
        }
        $public_dirname = trim($public_dirname, '\/');
        if($subdir){
            $subdir = trim($subdir, '\/');
            $public_dir = "$subdir/$public_dirname";
            $rewrite_base = "/$subdir/";
        }else{
            $public_dir = $public_dirname;
            $rewrite_base = "/";
        }
        // echo "Public: $public_dirname" . PHP_EOL;
        // echo "Subdir: $subdir";
        $template =
<<<MULTILINE
RewriteEngine On

# exception to .php files in public dir
RewriteRule ^($public_dirname/)(.+)\.php - [L,NC,R=403]
# If the URL starts with public/ or public$ then this is the last rule, apache will handle the rest.
RewriteRule ^($public_dirname/) - [L]

RewriteRule ^(.+)$ index.php [QSA,L,E=UNDER_REWRITE:YES]

# for debug purpose from php script, you may use this:
# RewriteRule ^(.+)$ index.php [QSA,L,E=REQUEST_FILENAME:%{REQUEST_FILENAME},E=REQUEST_URI:%{REQUEST_URI},E=DOCUMENT_ROOT:%{DOCUMENT_ROOT},E=DOCUMENT_X:%{DOCUMENT_ROOT}public%{REQUEST_URI}]

MULTILINE;
        return $template;
    }
}
