
<link rel="stylesheet" type="text/css" href="css/style.css">    
            


            <!-- STYLE PERFIL USUÁRIO-->

               <style>
                  /*img {
                     width: 30px; 
                     height: 30px;  
                     border-radius: 50%;
                  }
                  span{
                     color: white;
                     padding-left: 10px;
                  }*/
                  .menu-perfil{
                     margin-left: 100px;
                  }

                  @media screen and (max-width: 480px){
                   
                  span{
                     color: white;
                     padding-left: -350px;

                  .menu-perfil{
                     margin-left: 5px !important;
                  }
                  }
                  @media screen and (min-width: 481px) and (max-width: 768px){
                  .menu-perfil{
                     margin-left: 5px !important;
                  }     
                  }
               </style>

            <!-- FIM STYLE PERFIL USUÁRIO-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-xl">
    <!--<a class="navbar-brand" href="#">Cadastro online</a>-->
    <?php echo "<a class='navbar-brand' href='". URL ."home'>Cadastro Online</a>"; ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbars">
      <ul class="navbar-nav ms-auto">
      	<li class="nav-item active">
          <?php echo "<a class='nav-link' href='". URL ."home'>Home</a>"; ?><span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cadastrar</a>
                <div class="dropdown-menu" aria-labelledby="dropdown06">
                  <?php echo "<a class='dropdown-item' href='". URL ."cadastrar-categorias'>Categorias</a>"; ?>
                  <?php echo "<a class='dropdown-item' href='". URL ."cadastro-infografico'>Infográfico</a>"; ?>                  
                </div>
              </li>
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Inserir</a>
                <div class="dropdown-menu" aria-labelledby="dropdown06">
                  <?php echo "<a class='dropdown-item' href='". URL ."inserir-conteudo'>Conteúdo</a>"; ?>
                  <?php echo "<a class='dropdown-item' href='". URL ."inserir-historia'>História</a>"; ?>
                  <?php echo "<a class='dropdown-item' href='". URL ."inserir-obra'>Obra</a>"; ?>
                  <?php echo "<a class='dropdown-item' href='". URL ."inserir-grafico'>Gráfico</a>"; ?>
                </div>
              </li>
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Listar</a>
                <div class="dropdown-menu" aria-labelledby="dropdown06">
                  <?php echo "<a class='dropdown-item' href='". URL ."listar-infograficos'>Listar-Infográficos</a>"; ?>
                  <?php echo "<a class='dropdown-item' href='". URL ."listar-categorias'>Listar-Categorias</a>"; ?>
                </div>
              </li>
        
                    
        <li class="nav-item">
          <?php echo "<a class='nav-link' href='". URL ."sair'>Sair</a>"; ?>
        </li>
          </li>
          <div class="menu-perfil">
            <a class="" href="perfil">
            <span><?php echo $_SESSION['nome']; ?></span>
            <img class="img-perfil" src="images/perfil/semfoto.png">
            </a>
          </div>
      </ul>
      
    </div>
  </div>
</nav>
<?php

/* echo "<a href='". URL ."home'>Home</a><br>";
echo "<a href='". URL ."produtos'>Produtos</a><br>";
echo "<a href='". URL ."blog'>Blog</a><br>";
echo "<a href='". URL ."sobre'>Sobre</a><br>";
echo "<a href='". URL ."contato'>Contato</a><br><br>";*/
include_once './include/footer.php';