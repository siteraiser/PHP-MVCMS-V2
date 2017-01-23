Yo<?php 
class minification{
public $files = array('assets/css/menu.css');//,'assets/css/customfonts.css'
public $concat = true;
public $output_path = 'assets/css';


function minify( $css, $comments ){
    // Normalize whitespace
    $css = preg_replace( '/\s+/', ' ', $css );
 
    // leaving and empty /**/ will break the it!!!! Remove comment blocks, everything between /* and */, unless preserved with /*! ... */
    if( !$comments ){
        $css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
    }//if
     
    // Remove ; before }
    $css = preg_replace( '/;(?=\s*})/', '', $css );
 
    // Remove space after , : ; { } */ >
   $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
  /* Breaks search media query */
    // Remove space before , ; { } ( ) >
    $css = preg_replace( '/ (,|;|\{|}|>)/', '$1', $css ); 

    // Strips leading 0 on decimal values (converts 0.5px into .5px)
    $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

    // Strips units if value is 0 (converts 0px to 0)
    $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

    // Converts all zeros value into short-hand
    $css = preg_replace( '/0 0 0 0/', '0', $css );

    // Shortern 6-character hex color codes to 3-character where possible
   // $css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
  
    return trim( $css );
}//minify

function processFiles(){
        // array of minified css
        $css_result = [];
         
        foreach ( $this->files as $file ) {
            //read file content
            $file_content = file_get_contents( $file );
            //minify CSS and add it to the result array
            $css_result[] = $this->minify( $file_content, $this->comments );
        }//foreach
         
        // if the concat flag is true
        if( $this->concat ){
            // join the array of minified css
            $css_concat = implode( PHP_EOL, $css_result );
            // save to file
            file_put_contents($this->output_path . '/all.min.css', $css_concat);
            file_put_contents($this->output_path . '/all.min.php', $css_concat);
        }//if
        else{
        
            foreach ($css_result as $key => $css) {/* will fuck up your originals 
                //remove '.css' to add '.min.css'
                $filename = basename( $this->files[$key], '.css' ) . '.min.css';
                // save to file
                file_put_contents(BASE_URI.'/styles' . '/' . $filename, $css);*/
            }//for
        }//else
 
    }//processFiles

}

$doMinify = new minification;
$doMinify->processFiles();
echo'css minified';
?>