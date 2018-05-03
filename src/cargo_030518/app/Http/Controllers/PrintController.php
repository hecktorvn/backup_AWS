<?php
namespace App\Http\Controllers;

use Auth;
use Format;
use NFePHP\DA\Legacy\Common;
use NFePHP\DA\Legacy\Pdf;
/**
 * [PrintController]
 * Classe responsavel pela geração de PDF
 * Podendo ser personalizado capturando o FPDF
 * Pela função GetPDF()
 */
class PrintController extends Controller
{
    //publicas
    public $pdf;
    public $pdfDir = __DIR__;
    public $orientacao = 'P'; //ORIENTAÇÃO DO PAPEL (P - PORTATIL, L - LANDSCAPE)
    public $papel = 'A4'; //TIPO DO PAPEL
    public $sizeTitle = 8; //TAMANHO DA FONTE DO TITULO
    public $sizeContent = 6; //TAMANHO DA FONTE DO CONTEUDO
    public $margin = [7, 7, 7, 7]; //MARGENS DO PAPEL [Topo, Direita, Baixo, Esquerda]
    public $position = [7, 7]; //POSIÇÕES INICIAIS [X, Y]
    public $ConfigTable = [
        'TableBorder' => true, //DEFINE A BORDA DA TABLE BODY
        'AlignTable'  => [],   //ALINHAMENTO DOS ITENS DO TABLE
        'Zebra' => false, //ZEBRAR OS ITENS OU NÃO
        'sizeTitle' => 6,
        'sizeContent' => 6,
    ];
    public $sizePapel = [
        //SETANDO OS TAMANHOS DOS PAPAIS [Width, Height]
        'A4' => [210, 297]
    ];

    //privadas
    private $sizePage = [];
    private $Ay;

    /**
     * [__construct] Construtor da classe
     * @param string $papel      Tipo do Papel
     * @param string $orientacao P - Portatil, L - Landscape
     */
    public function __construct(String $papel='A4', String $orientacao='P'){
        $this->papel = $papel;
        $this->orientacao = $orientacao;
        $this->sizePage = $this->sizePapel[$this->papel];
        $this->startPage();
    }

    /**
     * [startPage] Iniciando o documento
     * @return PrintController
     */
    public function startPage($AddPage=true) : PrintController{
        $margin = $this->margin;
        $position = $this->position;
        $maxW = $this->sizePage[0];
        $maxH = $this->sizePage[1];

        $this->pdf = new Pdf($this->orientacao, 'mm', $this->papel);
        $this->pdf->SetAutoPageBreak(true, $margin[2]);
        $this->pdf->PageBreakTrigger = $maxW;

        //largura imprimivel em mm
        $this->wPrint = $maxW-($margin[3] + $position[0]);

        //comprimento imprimivel em mm
        $this->hPrint = $maxH-($margin[0] + $position[1]);

        // estabelece contagem de paginas
        $this->pdf->AliasNbPages();

        // fixa as margens
        $this->pdf->SetMargins($margin[3], $margin[0], $margin[1]);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetFillColor(255, 255, 255);

        // inicia o documento
        $this->pdf->Open();
        if($AddPage) $this->AddPage();

        $this->Fonte();
        return $this;
    }

    /**
     * [AddPage] Criando uma página
     */
    public function AddPage(){
        // adiciona a primeira página
        $this->pdf->AddPage($this->orientacao, $this->papel);
        $this->pdf->SetLineWidth(0.1);
    }

    /**
     * [DefaultPage] Prepara a impressão Default com Header e Footer
     * @param  String $title
     * @param  string $filtro
     * @param  array $margins
     * @return PrintController
     */
    public function DefaultPage(String $title, String $filtros='', Array $margins=[3, 3, 3, 3]) : PrintController{
        $this->margin = $margins;
        $this->startPage(false);
        $this->Footer();
        $this->Header($title, $filtros);
        $this->AddPage();
        return $this;
    }

    /**
     * [ConfigRect] Desenhando retangulo na posição atual
     * @param  integer $lineSize
     * @param  array $color
     * @param  array $fill
     * @return PrintController
     */
    public function ConfigRect(Float $lineSize=0.2, Array $color=[0, 0, 0], Array $fill=[]) : PrintController{
        $this->pdf->SetLineWidth($lineSize);
        if(!empty($fill)) $this->pdf->SetFillColor($fill[0], $fill[1], $fill[2]);
        $this->pdf->SetDrawColor($color[0], $color[1], $color[2]);
        return $this;
    }

    /**
     * [Rect] Desenhando o Retangulo
     * @param Float  $x     Posição X do Retangulo
     * @param Float  $y     Posição Y do Retangulo
     * @param Float  $w     Largura
     * @param Float  $h     Altura
     * @param string $style D - Draw, F - Fill
     * @return PrintController
     */
    public function Rect(Float $x, Float $y, Float $w, Float $h, string $style='DF') : PrintController{
        $this->pdf->Rect($x, $y, $w, $h, $style);
        $this->ConfigRect();
        return $this;
    }

    /**
     * [Cell] Desenhando um box CELL
     * @param Float $w       Tamanho do CELL
     * @param Float $h       Altura do CELL
     * @param String $txt    Texto a ser exibido
     * @param Object $border
     *    0: no border
     *    1: frame
     *    L: left
     *    T: top
     *    R: right
     *    B: bottom
     * @param String $align
     *    L or empty string: left align (default value)
     *    C: center
     *    R: right align
     * @param Boolean $fill  Caso TRUE preenche o CELL
     * @return PrintController
     */
    public function Cell($w, $h, String $txt, $border=0, String $align='L', bool $fill=null) : PrintController{
        $this->pdf->MultiCell($w, $h, $txt, $border, $align, $fill);
        if(!empty($this->Ay)){
            $this->pdf->SetY($this->Ay);
            $this->Ay = null;
        }

        return $this;
    }

    /**
     * [MoveCell description]
     * @param Float $x Distancia a ser movido o CELL
     * @return PrintController
     */
    public function MoveCell(Float $x, Float $y=null) : PrintController{
        $this->Ay = $this->pdf->GetY();
        if(!empty($y)) $this->pdf->SetY($y);
        if(!empty($x) && $x > 0) $this->pdf->Cell($x);
        return $this;
    }

    /**
     * [Fonte] Alterando a fonte
     * @param  string $family Fonte utilizada
     * @param  string $Style  Estilo Utilizado B - Bold, I - Italic
     * @param  integer $Size   Tamanho da Fonte
     * @return PrintController
     */
    public function Fonte(String $family='Arial', String $Style='', Float $Size=10) : PrintController{
        $this->pdf->SetFont($family, $Style, $Size);
        return $this;
    }

    /**
     * [HeadFilial] Seta o Header com os dados da Filial
     * @param  String $title
     * @param  String $filtros
     * @param  boolean $border
     * @return PrintController
     */
    public function Header(String $title, String $filtros=null, Bool $border=true) : PrintController{
        $margin = $this->margin;
        $size = $this->GetSize();
        $filial = Auth::user()->getFilial();
        $this->pdf->SetTitle($title);
        $print = $this;

        $this->pdf->CallHeader = function() use ($margin, $border, $filtros, $title, $size, $filial, $print){
            $h = 15;
            $w = $size[0] - ($margin[1] + $margin[3]);
            if($border) $print->Rect($margin[1], $margin[0], $w, $h, 'D');

            $y = $margin[0];
            $print->Fonte('Arial', 'B', 7);
            $print->MoveCell(0, $y)->Cell($w, 5, $filial->SOCIAL, false, 'C');

            $y += 3;
            $print->Fonte('Arial', '', 7);
            $endereco = "$filial->ENDERECO, $filial->NUMERO - $filial->BAIRRO";
            $print->MoveCell(0, $y)->Cell($w, 5, $endereco, false, 'C');

            $y += 3;
            $print->MoveCell(0, $y)->Cell($w/2, 5, $filial->CEP . '     ', false, 'R');
            $print->MoveCell($w/2, $y)->Cell($w/2, 5, "     $filial->CIDADE/$filial->UF", false);

            $y += 3;
            $print->MoveCell(0, $y)->Cell($w, 5, "$title - $filtros", false);
            $print->pdf->SetY($h + $margin[2]);
        };

        return $this;
    }

    /**
     * [Footer] Seta o Footer Padrão
     * @return PrintController
     */
    public function Footer() : PrintController{
        $operador = Auth::user()->NOME;
        $this->pdf->Footer = 'www.unicanet.com.br';
        $this->pdf->CallFooter = function(&$element) use ($operador){
            $element->SetFontSize(7);
            $datahora = date('d/m/Y \a\s H:i');
            $element->Cell(0, 20, "Impresso em {$datahora} por {$operador}", 0, 0, 'L');
            $element->SetX(0);

            $element->Cell(0, 20, utf8_decode('Página ') . $element->PageNo() . ' de {nb}', 0, 0, 'R');
            $element->SetX(0);
        };

        return $this;
    }

    /**
     * [GetSize] Retorna o Width e Height da pagina
     * @return Array [Weight, Height]
     */
    public function GetSize() : Array{
        $size = $this->sizePage;
        if($this->orientacao != 'P') $size = array_reverse($size);
        return $size;
    }

    /**
     * [GetPDF] Retorna o Pdf que contem o
     * FPDF extendido na sua estrutura
     * @return Pdf
     */
    public function GetPDF() : Pdf{
        return $this->pdf;
    }

    /**
     * [Table] Desenha um table de acordo com os dados passados
     * @param  Array $head
     * @param  Array $body
     * @param  Float $x
     * @param  Float $y
     * @return PrintController
     */
    public function Table(Array $head, Array $body, Float $x=null, Float $y=null) : PrintController{
        $Ax = $this->pdf->GetX();
        $Ay = $this->pdf->GetY();
        $nX = 0.0;

        $sizeDef = $this->ConfigTable['Size'] ?? null;
        if(!empty($x)) $this->pdf->SetX($x);
        if(!empty($y)) $this->pdf->SetY($y);

        //SETANDO HEADER
        $size = [];
        $nX = $this->SetHeadTable($Ay, $head, $body, $size, $sizeDef);

        //SETANDO BODY
        $this->SetBodyTable($Ay, $head, $body, $size, $sizeDef);

        if(!empty($x)) $this->pdf->SetX($Ax);
        if(!empty($y)) $this->pdf->SetY($Ay);
        return $this;
    }

    /**
     * [SetHeadTable] Adiciona o header do Table
     * @param  Float $Ay
     * @param  Array $head
     * @param  Array $body
     * @param  Array $size
     * @param  Array $sizeDef
     * @return Float
     */
    public function SetHeadTable(Float &$Ay, Array $head, Array $body, Array &$size, Array $sizeDef) : Float{
        $sizeTitle = $this->ConfigTable['sizeTitle'];
        $AlignTable = $this->ConfigTable['AlignTable'] ?? [];
        $Border = $this->ConfigTable['Border'] ?? true;
        $this->pdf->SetFontSize($sizeTitle);

        $iC = 0; $nX = 0.0;
        $Ax = $this->pdf->GetX();
        $h = $sizeTitle - 2;

        foreach($head as $i=>$name){
            if(!isset($size[$iC])){
                foreach(array_values($body) as $item){
                    $w = strlen($item[$i]) * ($sizeTitle / 3);
                    if(!isset($size[$iC]) || $w > $size[$iC]) $size[$iC] = $w;
                }

                $w = strlen($i) * 3;
                if($w > $size[$iC]) $size[$iC] = $w;
            }

            $w = $sizeDef[$iC] ?? $size[$iC];
            $maxLen = intval($w / ($sizeTitle / 3.5));
            $str = substr(utf8_decode($name), 0, $maxLen);

            if(!empty($this->ConfigTable['FillHeader'])){
                $fill = $this->ConfigTable['FillHeader'] ?? [];
                $this->pdf->SetFillColor($fill[0], $fill[1], $fill[2]);
            }

            $this->MoveCell($nX, $Ay)->Cell($w, $h, $str, $Border, $AlignTable[$iC] ?? 'L', isset($fill) ? true : false);

            $nX += $w;
            $iC++;
        }

        //SETANDO A BORDA
        if(!$Border) $this->Rect($Ax, $Ay, $nX, $h, 'D');

        $Ay += $h;
        return $nX;
    }

    /**
     * [SetBodyTable] Adiciona o conteudo e quebra caso maior que a página
     * @param  Float $Ay
     * @param  Array $head
     * @param  Array $body
     * @param  Array $size
     * @param  Array $sizeDef
     * @return Float
     */
    public function SetBodyTable(Float &$Ay, Array $head, Array $body, Array $size, Array $sizeDef) : Float{
        $sizeTitle = $this->ConfigTable['sizeTitle'];
        $sizeContent = $this->ConfigTable['sizeContent'];
        $AlignTable = $this->ConfigTable['AlignTable'];
        $TableBorder = $this->ConfigTable['Border'] ?? true;
        $this->pdf->SetFontSize($sizeContent);

        $sizePage = $this->GetSize();
        $x = $this->pdf->GetX();
        $y = $Ay;
        $iC = 0;
        $nX = 0.0;
        $hTot = 0;

        foreach($body as $iI=>$item){
            $iC = 0;
            $nX = 0.0;
            $h = $sizeContent-2;

            $this->pdf->SetFontSize($sizeContent);
            $zebra = $this->ConfigTable['Zebra'] && fmod($iI, 2) > 0;

            foreach($item as $iName=>$name){
                $w = $sizeDef[$iC] ?? $size[$iC];
                if(!in_array($iName, array_keys($head))) continue;

                $maxLen = intval($w / ($sizeContent/3.5));
                $str = substr(utf8_decode($name), 0, $maxLen);

                $this->pdf->SetFillColor(215, 215, 215);
                $this->MoveCell($nX, $Ay)->Cell($w, $h, $str, $TableBorder, $AlignTable[$iC] ?? 'L', $zebra);
                $nX += $w;
                $iC++;
            }

            $Ay += $h;
            $hTot += $h;
            if($Ay + ($sizeContent * 3) > $sizePage[1]){
                if(!$TableBorder) $this->Rect($x, $y, $nX, $hTot, 'D');
                $hTot = 0;

                $this->AddPage();
                $Ay = $this->pdf->GetY();
                $this->SetHeadTable($Ay, $head, $body, $size, $sizeDef);
            }
        }

        if(!$TableBorder) $this->Rect($x, $y, $nX, $hTot, 'D');
        return $Ay;
    }
    /**
     * [Print] Imprimindo o PDF
     * @param string $nome    Nome do arquivo a ser gerado
     * @param string $destino Destino
     * @param string $printer
     */
    public function Print(String $nome='', String $destino='I', String $printer=''){
        //monta
        $command = '';
        if ($nome == '') {
            $file = $this->pdfDir . '.pdf';
        } else {
            $file = $this->pdfDir . $nome;
        }

        if ($destino != 'I' && $destino != 'S' && $destino != 'F') {
            $destino = 'I';
        }

        if ($printer != '') {
            $command = "-P $printer";
        }

        $arq = $this->pdf->Output($file, $destino);
        if ($destino == 'S' && $command != '') {
            //aqui pode entrar a rotina de impress�o direta
            $command = "lpr $command $file";
            system($command, $retorno);
        }

        return $arq;
    }

    /**
     * [Render] Retorna o String do PDF
     * @return String
     */
    public function Render() : String{
        return $this->pdf->getPdf();
    }
}
