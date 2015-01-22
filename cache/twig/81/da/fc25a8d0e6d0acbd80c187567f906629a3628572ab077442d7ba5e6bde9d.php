<?php

/* productEdit.twig */
class __TwigTemplate_81dafc25a8d0e6d0acbd80c187567f906629a3628572ab077442d7ba5e6bde9d extends Twig_Template
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
\t\t\t<form id=\"update-product\" name=\"update-product\" method=\"post\" >
\t\t\t\t";
        // line 24
        echo (isset($context["table"]) ? $context["table"] : null);
        echo "
\t\t\t</form>
\t\t</div>




\t\t<div class=\"row medium-uncollapse large-collapse\">
\t\t\t<ul class=\"stack button-group\">
\t\t\t\t<li><a href=\"#\" class=\"button\" onclick=\"crudProduct('";
        // line 33
        echo (isset($context["id"]) ? $context["id"] : null);
        echo "','update');\" >Guardar</a></li>
\t\t\t</ul>\t\t
\t\t</div>
</div>
 \t<script src=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/function.js\"></script>
    <script src=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/fastclick.js\"></script>
\t<script src=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/modernizr.js\"></script>
\t<script src=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/js/vendor/jquery.cookie.js\"></script>
\t<script src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : null), "source"), "html", null, true);
        echo "home/fancybox/jquery.fancybox.js\"></script>

\t


</body>
</html>\t


";
    }

    public function getTemplateName()
    {
        return "productEdit.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 41,  93 => 40,  89 => 39,  85 => 38,  81 => 37,  74 => 33,  62 => 24,  46 => 11,  40 => 8,  36 => 7,  32 => 6,  28 => 5,  24 => 4,  19 => 1,);
    }
}
