<?php
include('header.php');
?>
        <div class="box container">
            <!-- Titulo Buscador -->
            <div class="search-title">
                Quick search
            </div>
            
            <!-- Buscador google -->
            <form target="_blank" action="https://www.google.com/search">
                <div class="form-group">
                    <label for="fq">Google Search</label>
                    <input class="form-control" type="text" id="fq" name="q" required placeholder="Your query for Google...">
                </div>
                <div class="text-center">
                    <button class="btn btn-success">Go Google</button>
                </div>
            </form>

            <!-- Buscador Wikipedia -->
            <form target="_blank" action="https://es.wikipedia.org/w/">
                <div class="form-group">
                    <label for="fsearch">Wikipedia Search</label>
                    <input class="form-control" type="text" id="fsearch" name="search" required placeholder="Your query for Wikipedia...">
                </div>
                <div class="text-center">
                    <button class="btn btn-success">Go Wikipedia</button>
                </div>
            </form>
        </div>


        <div class="container" style="margin-top: 200px;">
            <div class="row">
                <div class="col-md-6 text-center">
                        <label for="name" class="control-label">Fly from Barcelona to London</label>
                        <a href="https://www.google.es/flights/#search;f=BCN,YJB;t=LHR,LGW,STN,LCY,LTN,SEN,QQS;d=2018-03-17;r=2018-03-31" class="icon">
                            <img class="img-responsive" border="0" alt="BCN to LON" src="img/london.jpg">
                        </a>
                </div>
                <div class="col-md-6 text-center">
                        <label for="name" class="control-label">Fly from Barcelona to London</label>
                        <a href="https://www.google.es/flights/#search;f=BCN,YJB;t=FCO,CIA,IRT,XRJ;d=2018-03-17;r=2018-03-31" class="icon">
                            <img class="img-responsive" border="0" alt="BCN to ROM" src="img/italy.jpg">
                        </a>
                </div>
            </div>
        </div>

<?php
include('footer.html');
?>
        
