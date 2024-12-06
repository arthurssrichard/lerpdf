<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Leitor de pdf</h1>
    <p>
    <?php
        require __DIR__ . '/vendor/autoload.php';
        use Smalot\PdfParser\Parser;
        // CÓDIGO BÁSICO
        $path = 'C:\Users\arthu\desktop\transicao1.pdf';
        $parser = new Parser();
        $pdf = $parser->parseFile($path);

        // CÓDIGO PARA PEGAR ÍNDICE DE MENSAGENS
        $textCapitulos = getPagesArray([5,6,7,8,9], $pdf); 
        $textCapitulos = filterIndex($textCapitulos, "MENSAGENS","289");

        exibirMensagem($textCapitulos, $pdf, "Tornai vossas vidas o mais simples possível");
        //exibirMensagem($textCapitulos, $pdf, "E a terra sofre!");

        //echo"sex";
        /* FUNÇÕES */
        function getPagesArray($array, $pdf){
            $text = '';
            foreach($array as $page){
                $text .= $pdf->getPages()[$page-1]->getText() ."\n";
            }
            return $text;
        }

        function filterChapter($text, $start, $end){
            $startPos = strpos($text, $start);
            $endPos = strpos($text, $end);

            if($endPos !== false){
                return substr($text, $startPos, $endPos - $startPos);
            }

            return substr($text, $startPos);
        }

        function filterIndex($text, $start, $end){
            $startPos = strpos($text, $start) + strlen($start);
            $endPos = strpos($text, $end) + strlen($end);

            if($endPos !== false){
                return substr($text, $startPos, $endPos - $startPos);
            }

            return substr($text, $startPos);
        }

        function exibirMensagem($listMessages, $pdf, $title){

            // pega as páginas do capítulo (onde começa e onde termina)
            $title = preg_quote($title, '/');

            $pattern1 = "/$title.*?(\d+)\s\d+\.\s*.*?(\d+)/";
            $pages = null;
            if(preg_match($pattern1, $listMessages, $matches)){
                $pages = [$matches[1],$matches[2]];
                $pages = range($pages[0],$pages[1]);
            }

            $text = '';
            echo"Páginas: $pages[0] e $pages[1]";
            foreach($pages as $page){
                $text .= $pdf->getPages()[$page-1]->getText();
            }
            
            echo "<br><br>Texto original: <br> $text";

            $titleChapter =  "TORNAI VOSSAS VIDAS O MAIS SIMPLES POSSÍVEL"; //strtoupper($title);
            //$pattern2 = "/($titleChapter.*?)(?=\d+\.\s|$|\.{3,})/siu"; //"/([\S\s]*)/" para testar tudo
            $pattern2 = "/.*?($titleChapter.?*)/";
            if(preg_match($pattern2, $text, $matches)){
                $capitulo =  $matches[1];
            }
            echo "<br><br>Texto procurado: $titleChapter";
            echo "<br><br>Texto Filtrado: <br> $capitulo";
        }
    ?>

    </p>
</body>
</html>
