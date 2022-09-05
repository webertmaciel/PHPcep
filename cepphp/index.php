<?php
session_start();
require_once 'banco.php';
$l = new Localiz("banco", "localhost", "root", "");

$id = filter_input(INPUT_GET, 'id');
if (!empty($id)) {
    $delete = $l->deleteLoc($id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConsultaCep</title>
    <!-- CSS BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- BOOTSTRAP ICON -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <!-- FONTES DO GOOGLE -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <!-- ESTILO SEPARADO -->
    <style>
        body {
            background: rgb(2, 0, 36);
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(54, 131, 72, 1) 35%, rgba(0, 212, 255, 1) 100%);
        }

        .style1 {
            align-content: center;
            font-family: 'Lobster', cursive;
            font-size: 100px;
            color: white;
        } 

        svg {
            width: 3em;
            height: 3em;
            color: white;
        }

         #esquerda {
            width: 35%;
            height: 500px;
            float: left;
        }

        #direita {
            width: 50%;
            height: 500px;
            float: left;
        }


        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>

<body>
    <?php


    if (isset($_POST['cep'])) {
        $cep = addslashes((isset($_POST['cep'])) ? $_POST['cep'] : '');
        $logradouro = addslashes((isset($_POST['logradouro'])) ? $_POST['logradouro'] : '');
        $complemento = addslashes((isset($_POST['complemento'])) ? $_POST['complemento'] : '');
        $bairro = addslashes((isset($_POST['bairro'])) ? $_POST['bairro'] : '');
        $localidade = addslashes((isset($_POST['localidade'])) ? $_POST['localidade'] : '');
        $uf = addslashes(isset($_POST['uf']) ? $_POST['uf'] : '');
        $ibge = addslashes((isset($_POST['ibge'])) ? $_POST['ibge'] : '');



        if (!empty($cep)) {

            $data = $l->findByCep($cep);

            if ($data == false) {
                $url = "https://viacep.com.br/ws/" . $cep . "/json";
                $json = file_get_contents($url);
                $data = json_decode($json, true);
            }
        }


        if (!empty($cep) && !empty($uf)) {
            // cadastrar
            if (!$l->salvarLoc($cep, $logradouro,  $bairro, $localidade, $uf, $ibge, $complemento)); {
                header('Location: index.php');
                exit();
            }
        } elseif (empty($cep)) {
            $_SESSION['flash'] = "Preencha o CEP";
            header('Location: index.php');
            exit();
        }
    }





    ?>
    <div>
        <label class="style1">Informa Cep</label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
        </svg>
    </div>

    <section  id="esquerda">
        <form action="" method="post">
            
                <label for="cep">
                    <?php if ($_SESSION['flash']) : ?>
                        <span class="alert alert-danger">
                            <?= $_SESSION['flash'] ?>
                            <?php $_SESSION['flash'] = ''; ?>
                        </span>
                    <?php endif ?></label>

        <div class="col px-md-5"><div class="p-1 border bg-light">
            <label class="form-label">CEP:</label>
                <input class="form-control" type="text" name="cep" value='<?php if (isset($data)) echo $data["cep"] ?>' id="cep">
                <button value="salvar" type="submit" class="btn btn-primary" class="btn btn-primary">Buscar</button>
        </div></div>
            
        </form>
        <form method="post">
            
                <input type="hidden" name="cep" value="<?php if (isset($data)) echo $data["cep"] ?>">
            
            <div class="col px-md-5"><div class="p-1 border bg-light">
                <label class="form-label">logradouro:</label>
                <input class="form-control" type="text" name="logradouro" value='<?php if (isset($data)) echo $data["logradouro"] ?>' id="logradouro">
            </div>
            <div><div class="p-1 border bg-light">
                <label class="form-label">Complemento:</label>
                <input class="form-control" type="text" name="complemento" value='<?php if (isset($data)) echo $data["complemento"] ?>' id="complemento">
            </div>
            <div><div class="p-1 border bg-light">
                <label class="form-label">Bairro:</label>
                <input class="form-control" type="text" name="bairro" value='<?php if (isset($data)) echo $data["bairro"] ?>' id="bairro">
            </div>
            <div><div class="p-1 border bg-light">
                <label class="form-label">Localidade:</label>
                <input class="form-control" type="text" name="localidade" value='<?php if (isset($data)) echo $data["localidade"] ?>' id="localidade">
            </div>
            <div><div class="p-1 border bg-light">
                <label class="form-label">Estado:</label>
                <input class="form-control" type="text" name="uf" value='<?php if (isset($data)) echo $data["uf"] ?>' id="uf">
            </div>
            <div><div class="p-1 border bg-light">
                <label class="form-label">IBGE:</label>
                <input class="form-control" type="text" name="ibge" value='<?php if (isset($data)) echo $data["ibge"] ?>' id="ibge">
                <button value="salvar" type="submit" class="btn btn-primary" class="btn btn-primary"><?= (isset($data)) ? 'Salvar' : 'Salvar' ?></button>
            </div>
        </form>

    </section>
    <section id="direita" >

        <?php
        $dados = $l->buscarDados();

        ?>

        <table class="table">
            <thead>
                <th>Cep</th>
                <th>Logradouro</th>
                <th>Complemento</th>
                <th>Bairro</th>
                <th>Localidade</th>
                <th>uf</th>
                <th>IBGE</th>
            </thead>
            <tbody>
                <?php foreach ($dados as $dado) : ?>
                    <tr>
                        <td><?= $dado['cep'] ?></td>
                        <td><?= $dado['logradouro'] ?></td>
                        <td><?= $dado['complemento'] ?></td>
                        <td><?= $dado['bairro'] ?></td>
                        <td><?= $dado['localidade'] ?></td>
                        <td><?= $dado['uf'] ?></td>
                        <td><?= $dado['ibge'] ?></td>
                        <td><a class="btn btn-danger" href="index.php?id=<?= $dado['id'] ?>">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script src="https://unpkg.com/imask"></script>
    <script>
        var element = document.getElementById('cep');
        var maskOptions = {
            mask: '00000-000'
        };
        var mask = IMask(element, maskOptions);
    </script>
</body>

</html>