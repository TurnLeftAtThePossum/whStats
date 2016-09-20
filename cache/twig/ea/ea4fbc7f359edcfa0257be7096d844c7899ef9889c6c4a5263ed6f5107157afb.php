<?php

/* index.html */
class __TwigTemplate_b341154480e7c5547cc7b239a5a9451f7e8e48b8578d49a2e104b84297f0c816 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("header.html", "index.html", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'version' => array($this, 'block_version'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "header.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "WH Stats 2.0";
    }

    // line 3
    public function block_version($context, array $blocks = array())
    {
        echo "2.0";
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "
<!-- Modal Structure -->
  <div id=\"modal1\" class=\"modal\">
    <div class=\"modal-content\">
      <h4>Loading Stats</h4>
      <div class=\"preloader-wrapper big active\">
        <div class=\"spinner-layer spinner-blue-only\">
          <div class=\"circle-clipper left\">
            <div class=\"circle\"></div>
          </div><div class=\"gap-patch\">
            <div class=\"circle\"></div>
          </div><div class=\"circle-clipper right\">
            <div class=\"circle\"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--Intro-->
<div id=\"intro\" class=\"section scrollspy\">
  <div class=\"container\">
    <div class=\"row\">
      <div  class=\"col s12\">
        <h2 class=\"center header text_h2\"><span id=\"refreshText\">Time until next refresh </span><span id=\"countdown\" class=\"span_h2\"></span></h2>
      </div>
    </div>
  </div>
</div>
<!--Essential Stats-->
<div id=\"essentialStats\" class=\"section scrollspy\">
  <div class=\"container\">
    <div class=\"row center-align\">
      <div class=\"col s6 m6 14\">
        <h5 class=\"promo-caption\">Total Kills <span class=\"totalKills\"></span></h5>
      </div>
      <div class=\"col s6 m6 14\">
        <h5 class=\"promo-caption\">Total ISK <span class=\"totalISK\"></span></h5>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col s12 center\"><i>Excluding Structure Kills</i></div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top WH Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestTotalImg kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestTotalKill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top Solo Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestSoloImg kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestSoloKill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Largest Loss To An NPC</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestNPCImg kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestNPCKill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
            <div class=\"kill-content white-text\">
              <span class=\"kill-title\">Top C1 Kill</span><br>
              <div class=\"killLine\">
                <img src=\"../img/blank_symbol.png\" class=\"biggestC1Img kill-img eveimage img-rounded\">
                <a href=\"\" class=\"biggestC1Kill kill-text\" target=\"_blank\">None</a>
              </div>
            </div>
          </div>
        </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top C2 Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestC2Img kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestC2Kill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top C3 Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestC3Img kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestC3Kill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top C4 Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestC4Img kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestC4Kill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top C5 Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestC5Img kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestC5Kill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"card kill-card blue-grey lighten-1\">
          <div class=\"kill-content white-text\">
            <span class=\"kill-title\">Top C6 Kill</span><br>
            <div class=\"killLine\">
              <img src=\"../img/blank_symbol.png\" class=\"biggestC6Img kill-img eveimage img-rounded\">
              <a href=\"\" class=\"biggestC6Kill kill-text\" target=\"_blank\">None</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Big Graphs-->
<div id=\"bigGraphs\" class=\"section scrollspy\">
  <div class=\"container\">
    <div class=\"row\">
      <div class=\"col s12\">
        <h5 class=\"promo-caption periodStats\">Hourly Kills</h5>
        <div class=\"big-chart-holder\">
          <canvas id=\"chartHour\" width=\"400\" height=\"400\"></canvas>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col s12\">
        <h5 class=\"promo-caption\">ISK Killed (Billions)</h5>
        <div class=\"big-chart-holder\">
          <canvas id=\"chartISKHour\" width=\"400\" height=\"400\"></canvas>
        </div>
      </div>
    </div>
    <div class=\"row\">
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C1 Average Kill</h5>
          <span class=\"updates\" id=\"c1Avg\"></span>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C2 Average Kill</h5>
          <span class=\"updates\" id=\"c2Avg\"></span>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C3 Average Kill</h5>
          <span class=\"updates\" id=\"c3Avg\"></span>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C4 Average Kill</h5>
          <span class=\"updates\" id=\"c4Avg\"></span>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C5 Average Kill</h5>
          <span class=\"updates\" id=\"c5Avg\"></span>
        </div>
      </div>
      <div class=\"col s12 m4 14\">
        <div class=\"center promo promo-example\">
          <h5 class=\"promo-caption\">C6 Average Kill</h5>
          <span class=\"updates\" id=\"c6Avg\"></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Breakdowns-->
<div id=\"breakdowns\" class=\"section scrollspy\">
    <div class=\"container\">
        <h2 class=\"header text_b\">Breakdowns</h2>
        <div class=\"row\">
            <div class=\"col s12\">
                <h5 class=\"promo-caption\">Cap Kill Breakdown</h5>
            </div>
            <div class=\"col s12 m4 14\">
                <h5 class=\"promo-caption\">Carrier Kills</h5>
                <div class=\"small-chart-holder\">
                  <canvas id=\"chartTotalCarrier\" width=\"400\" height=\"400\"></canvas>
                </div>
            </div>
            <div class=\"col s12 m4 14\">
                <h5 class=\"promo-caption\">Dread Kills</h5>
                <div class=\"small-chart-holder\">
                  <canvas id=\"chartTotalDread\" width=\"400\" height=\"400\"></canvas>
                </div>
            </div>
            <div class=\"col s12 m4 14\">
                <h5 class=\"promo-caption\">Force Auxiliary Kills</h5>
                <div class=\"small-chart-holder\">
                  <canvas id=\"chartTotalFAX\" width=\"400\" height=\"400\"></canvas>
                </div>
            </div>
        </div>

        <div class=\"row\">
            <div class=\"col s12\">
                <h5 class=\"promo-caption\">Ship Class Breakdowns</h5>
                <div class=\"breakdowns-holder\">
                  <canvas id=\"chartBreakdowns\" width=\"400\" height=\"1000\"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Footer-->
<footer id=\"contact\" class=\"page-footer default_color scrollspy\">
    <div class=\"footer-copyright default_color\">
        <div class=\"container\">
            Made by <a class=\"white-text\" href=\"https://discord.gg/0keRDoXN2Cxw6PIY\" target=\"_blank\">Aekro</a> of <a class=\"white-text\" href=\"http://takeshis-castle.com/\" target=\"_blank\">[TAKSH]</a>. Thanks to <a class=\"white-text\" href=\"http://materializecss.com/\">materializecss</a>.
            Try <a class=\"white-text\" href=\"http://eve-vippy.com/\" target=\"_blank\">Vippy</a> as your next WH Mapper!
        </div>
    </div>
</footer>

  <!-- Compiled and minified JavaScript -->
  <script type=\"text/javascript\" src=\"https://code.jquery.com/jquery-2.1.1.min.js\"></script>
  <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js\" integrity=\"sha256-0rguYS0qgS6L4qVzANq4kjxPLtvnp5nn2nB5G1lWRv4=\" crossorigin=\"anonymous\"></script>
  <script src=\"https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js\"></script>

  <!--  Scripts-
  <script src=\"min/plugin-min.js\"></script>-->
  <script src=\"min/custom-min.js\"></script>
  <script src=\"js/index.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 5,  42 => 4,  36 => 3,  30 => 2,  11 => 1,);
    }
}
/* {% extends "header.html" %}*/
/* {% block title %}WH Stats 2.0{% endblock %}*/
/* {% block version %}2.0{% endblock %}*/
/* {% block content %}*/
/* */
/* <!-- Modal Structure -->*/
/*   <div id="modal1" class="modal">*/
/*     <div class="modal-content">*/
/*       <h4>Loading Stats</h4>*/
/*       <div class="preloader-wrapper big active">*/
/*         <div class="spinner-layer spinner-blue-only">*/
/*           <div class="circle-clipper left">*/
/*             <div class="circle"></div>*/
/*           </div><div class="gap-patch">*/
/*             <div class="circle"></div>*/
/*           </div><div class="circle-clipper right">*/
/*             <div class="circle"></div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* <!--Intro-->*/
/* <div id="intro" class="section scrollspy">*/
/*   <div class="container">*/
/*     <div class="row">*/
/*       <div  class="col s12">*/
/*         <h2 class="center header text_h2"><span id="refreshText">Time until next refresh </span><span id="countdown" class="span_h2"></span></h2>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* <!--Essential Stats-->*/
/* <div id="essentialStats" class="section scrollspy">*/
/*   <div class="container">*/
/*     <div class="row center-align">*/
/*       <div class="col s6 m6 14">*/
/*         <h5 class="promo-caption">Total Kills <span class="totalKills"></span></h5>*/
/*       </div>*/
/*       <div class="col s6 m6 14">*/
/*         <h5 class="promo-caption">Total ISK <span class="totalISK"></span></h5>*/
/*       </div>*/
/*     </div>*/
/*     <div class="row">*/
/*       <div class="col s12 center"><i>Excluding Structure Kills</i></div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top WH Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestTotalImg kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestTotalKill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top Solo Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestSoloImg kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestSoloKill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Largest Loss To An NPC</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestNPCImg kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestNPCKill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*     <div class="row">*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*             <div class="kill-content white-text">*/
/*               <span class="kill-title">Top C1 Kill</span><br>*/
/*               <div class="killLine">*/
/*                 <img src="../img/blank_symbol.png" class="biggestC1Img kill-img eveimage img-rounded">*/
/*                 <a href="" class="biggestC1Kill kill-text" target="_blank">None</a>*/
/*               </div>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top C2 Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestC2Img kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestC2Kill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top C3 Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestC3Img kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestC3Kill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*     <div class="row">*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top C4 Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestC4Img kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestC4Kill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top C5 Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestC5Img kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestC5Kill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="card kill-card blue-grey lighten-1">*/
/*           <div class="kill-content white-text">*/
/*             <span class="kill-title">Top C6 Kill</span><br>*/
/*             <div class="killLine">*/
/*               <img src="../img/blank_symbol.png" class="biggestC6Img kill-img eveimage img-rounded">*/
/*               <a href="" class="biggestC6Kill kill-text" target="_blank">None</a>*/
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* <!--Big Graphs-->*/
/* <div id="bigGraphs" class="section scrollspy">*/
/*   <div class="container">*/
/*     <div class="row">*/
/*       <div class="col s12">*/
/*         <h5 class="promo-caption periodStats">Hourly Kills</h5>*/
/*         <div class="big-chart-holder">*/
/*           <canvas id="chartHour" width="400" height="400"></canvas>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*     <div class="row">*/
/*       <div class="col s12">*/
/*         <h5 class="promo-caption">ISK Killed (Billions)</h5>*/
/*         <div class="big-chart-holder">*/
/*           <canvas id="chartISKHour" width="400" height="400"></canvas>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*     <div class="row">*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C1 Average Kill</h5>*/
/*           <span class="updates" id="c1Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C2 Average Kill</h5>*/
/*           <span class="updates" id="c2Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C3 Average Kill</h5>*/
/*           <span class="updates" id="c3Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C4 Average Kill</h5>*/
/*           <span class="updates" id="c4Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C5 Average Kill</h5>*/
/*           <span class="updates" id="c5Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*       <div class="col s12 m4 14">*/
/*         <div class="center promo promo-example">*/
/*           <h5 class="promo-caption">C6 Average Kill</h5>*/
/*           <span class="updates" id="c6Avg"></span>*/
/*         </div>*/
/*       </div>*/
/*     </div>*/
/*   </div>*/
/* </div>*/
/* */
/* <!--Breakdowns-->*/
/* <div id="breakdowns" class="section scrollspy">*/
/*     <div class="container">*/
/*         <h2 class="header text_b">Breakdowns</h2>*/
/*         <div class="row">*/
/*             <div class="col s12">*/
/*                 <h5 class="promo-caption">Cap Kill Breakdown</h5>*/
/*             </div>*/
/*             <div class="col s12 m4 14">*/
/*                 <h5 class="promo-caption">Carrier Kills</h5>*/
/*                 <div class="small-chart-holder">*/
/*                   <canvas id="chartTotalCarrier" width="400" height="400"></canvas>*/
/*                 </div>*/
/*             </div>*/
/*             <div class="col s12 m4 14">*/
/*                 <h5 class="promo-caption">Dread Kills</h5>*/
/*                 <div class="small-chart-holder">*/
/*                   <canvas id="chartTotalDread" width="400" height="400"></canvas>*/
/*                 </div>*/
/*             </div>*/
/*             <div class="col s12 m4 14">*/
/*                 <h5 class="promo-caption">Force Auxiliary Kills</h5>*/
/*                 <div class="small-chart-holder">*/
/*                   <canvas id="chartTotalFAX" width="400" height="400"></canvas>*/
/*                 </div>*/
/*             </div>*/
/*         </div>*/
/* */
/*         <div class="row">*/
/*             <div class="col s12">*/
/*                 <h5 class="promo-caption">Ship Class Breakdowns</h5>*/
/*                 <div class="breakdowns-holder">*/
/*                   <canvas id="chartBreakdowns" width="400" height="1000"></canvas>*/
/*                 </div>*/
/*             </div>*/
/*         </div>*/
/*     </div>*/
/* </div>*/
/* */
/* <!--Footer-->*/
/* <footer id="contact" class="page-footer default_color scrollspy">*/
/*     <div class="footer-copyright default_color">*/
/*         <div class="container">*/
/*             Made by <a class="white-text" href="https://discord.gg/0keRDoXN2Cxw6PIY" target="_blank">Aekro</a> of <a class="white-text" href="http://takeshis-castle.com/" target="_blank">[TAKSH]</a>. Thanks to <a class="white-text" href="http://materializecss.com/">materializecss</a>.*/
/*             Try <a class="white-text" href="http://eve-vippy.com/" target="_blank">Vippy</a> as your next WH Mapper!*/
/*         </div>*/
/*     </div>*/
/* </footer>*/
/* */
/*   <!-- Compiled and minified JavaScript -->*/
/*   <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>*/
/*   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" integrity="sha256-0rguYS0qgS6L4qVzANq4kjxPLtvnp5nn2nB5G1lWRv4=" crossorigin="anonymous"></script>*/
/*   <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>*/
/* */
/*   <!--  Scripts-*/
/*   <script src="min/plugin-min.js"></script>-->*/
/*   <script src="min/custom-min.js"></script>*/
/*   <script src="js/index.js"></script>*/
/* {% endblock %}*/
/* */