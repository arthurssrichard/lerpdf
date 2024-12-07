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
        $path = './transicao1.pdf';
        $parser = new Parser();
        $pdf = $parser->parseFile($path);

        // CÓDIGO PARA PEGAR ÍNDICE DE MENSAGENS
        $textCapitulos = getPagesArray([5,6,7,8,9], $pdf); 
        $textCapitulos = filterIndex($textCapitulos, "MENSAGENS","289");

        //exibirMensagem($textCapitulos, $pdf, "Um guerreiro da luz não pode ser pego de surpresa");
        exibirMensagem($textCapitulos, $pdf, "Acordai, irmãos. O final de tempos é uma realidade que
não podeis ignorar");

        echo "<br><br><br><br>".$textCapitulos;
        
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

        function filterIndex($text, $start, $end) {
            $startPos = strpos($text, $start) + strlen($start);
            $endPos = strpos($text, $end);
        
            // Extrai o texto entre o início e o fim
            $extractedText = ($endPos !== false)
                ? substr($text, $startPos, $endPos - $startPos)
                : substr($text, $startPos);
        
            // Normaliza o índice: une linhas quebradas
            return normalizeIndex($extractedText);
        }
        
        function normalizeIndex($text) {
            // Remove quebras de linha seguidas de espaços
            $text = preg_replace('/\n\s*/', ' ', $text);
        
            // Remove espaços extras
            $text = preg_replace('/\s+/', ' ', $text);
        
            // Retorna o índice normalizado
            return trim($text);
        }
        

        function exibirMensagem($listMessages, $pdf, $title){

            // Ajuste o padrão para capturar múltiplas linhas
            
            $escapedTitle = preg_quote($title, "/");
            $escapedTitle = preg_replace('/\s+/','\\s*',$escapedTitle);
            $pattern1 = '/'.$escapedTitle.'[\s\S]*?(\d+)\s\d+\.\s*[\s\S]*?(\d+)/';
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
            
            $text = preg_replace("/([A-Z]+)\s\s\s([A-Z]+)/", "$1 $2", $text);
            
            $escapedTitle = strtoupper($title);
            $escapedTitle = preg_replace('/\s+/','\\s*',$escapedTitle);
            $pattern2 = "/($escapedTitle.*?)(?=\d+\.\s|$|\.{4,})/siu";
            //$pattern2 = '/('.$titleChapter.'[\s\S]*?)(?=\d+\.\s|$|\.{4,})/siu';

            if(preg_match($pattern2, $text, $matches)){
                $capitulo =  $matches[1];
            }
            echo "<br><br>Texto procurado: $escapedTitle";
            echo "<br><br>Texto Filtrado: <br> $capitulo";
        }

        // function filterIndex($text, $start, $end){
        //     $startPos = strpos($text, $start) + strlen($start);
        //     $endPos = strpos($text, $end) + strlen($end);

        //     if($endPos !== false){
        //         return substr($text, $startPos, $endPos - $startPos);
        //     }

        //     return substr($text, $startPos);
        // }
    ?>

    </p>
</body>
</html>

