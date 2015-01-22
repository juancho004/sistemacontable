<?php

/* productList.twig */
class __TwigTemplate_a31b40272b7c98e784254bfa7fd4fe61e32ce9f4cb300ba6603ebe8ffdf71066 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
    <script src=\"";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/jquery.js\"></script>
    <script src=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/foundation.min.js\"></script>
    <link rel=\"stylesheet\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/css/foundation.css\" />
    <link rel=\"stylesheet\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/css/style.css\" />
\t<link href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\" />

    <script type=\"text/javascript\">
    var basepath = \"";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : null), "request"), "basepath"), "html", null, true);
        echo "\";

\t\t\$(document).ready(function(){
\t\t});
    </script>
</head>
<body>



\t<div class=\"row full-height split-page\" style=\"padding:20px;\">
\t\t<div id=\"main-tab\">
\t\t\t<table>
\t\t\t\t<thead>
\t\t\t\t\t<tr >
\t\t\t\t\t\t<th width=\"200\" >Nombre</th>
\t\t\t\t\t\t<th width=\"400\" >Descripción</th>
\t\t\t\t\t\t<th width=\"150\" >Precio Proveedor</th>
\t\t\t\t\t\t<th width=\"150\" >Precio Usuario</th>
\t\t\t\t\t\t<th width=\"150\" ></th>
\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t\t";
        // line 34
        echo (isset($context["table"]) ? $context["table"] : null);
        echo "
\t\t\t\t</tbody>
\t\t\t</table>
\t\t</div>

</div>
 \t<script src=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/function.js\"></script>
    <script src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/fastclick.js\"></script>
\t<script src=\"";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/modernizr.js\"></script>
\t<script src=\"";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/jquery.cookie.js\"></script>
\t<script src=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/fancybox/jquery.fancybox.js\"></script>

\t


</body>
</html>\t


";
    }

    public function getTemplateName()
    {
        return "productList.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 44,  93 => 43,  89 => 42,  85 => 41,  81 => 40,  72 => 34,  46 => 11,  40 => 8,  36 => 7,  32 => 6,  28 => 5,  24 => 4,  19 => 1,);
    }
}
