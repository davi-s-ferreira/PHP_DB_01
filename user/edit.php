<?php

// Inclui arquivo de configuração
require_once $_SERVER['DOCUMENT_ROOT'] . "/_config.php";

/*******************************************
 * Seu código PHP desta página entra aqui! *
 *******************************************/

// Variáveis do script
$form['feedback'] = '';
$show_form = true;

// Se não estiver logado, vai para a 'index'.
if (!isset($_COOKIE['user'])) header('Location: /');

if (isset($_POST['send-profile'])) :

    // debug($_POST);

    // Obtém os valores dos campos, sanitiza e armazena nas variáveis.
    $form['user_name'] = sanitize('name', 'string');
    $form['user_birth'] = sanitize('birth', 'string');
    $form['user_email'] = $user['user_email'];
    $form['password'] = sanitize('password', 'string');

    // Verifica se todos os campos form preenchidos
    if ($form['user_name'] === '' or $form['user_birth'] === '' or $form['password'] === '') :
        $form['feedback'] = '<h3 style="color:red">Erro: por favor, preencha todos os campos!</h3>';

    // Verifica se a data é válida
    elseif (!validate_date($form['user_birth'])) :
        $form['feedback'] = '<h3 style="color:red">Erro: a data de nascimento está incorreta!</h3>';
        $form['user_birth'] = $user['user_birth'];
    else :

        // String de atualização
        $sql = <<<SQL

UPDATE users 
SET 
    user_name = '{$form['user_name']}',
    user_birth = '{$form['user_birth']}'
WHERE user_id = '{$user['user_id']}' 
AND user_password = SHA2('{$form['password']}', 512);

SQL;

        // Executa a query
        $res = $conn->query($sql);

        // Testa o resultado da atualização
        $result = $conn->affected_rows;

        // Se não atualizou...
        if ($result == 0) :
            $form['feedback'] = '<h3 style="color:red">Erro: a senha está incorreta ou nada foi atualizado!</h3>';

        // Se deu erro no SQL...
        elseif ($result == -1) :
            $form['feedback'] = '<h3 style="color:red">Erro: falha no acesso ao banco de dados!</h3>';

        // Se deu tudo certo...
        else :

            // Obtém somente primeiro nome do rementente.
            $first_name = explode(" ", $form['user_name'])[0];

            // Cria mensagem de confirmação.
            $form['feedback'] = <<<OUT
                    
                <h3>Olá {$first_name}!</h3>
                <p>Seu cadastro foi atualizado com sucesso.</p>
                <p><em>Obrigado...</em></p>
                <script>
                setTimeout(() => {
                    
                }, 4000); 
                </script>
               
OUT;

            // Oculto o formulário.
            $show_form = false;

        endif;

    endif;

else :

    // Obtendo dados do usuário direto do banco de dados.
    $sql = <<<SQL

SELECT * FROM `users`
WHERE user_id = '{$user['user_id']}'
AND user_status = 'on';

SQL;

    // Executa a consulta
    $res = $conn->query($sql);

    // Se não retornar nada, volta para profile.
    if ($res->num_rows !== 1) header('Location: /user/profile.php');

    // Associa os dados ao formulário
    $form = $res->fetch_assoc();

    // Variáveis do script
    $form['feedback'] = '';

endif; // if (isset($_POST['send-profile'])) :

/*********************************************
 * Seu código PHP desta página termina aqui! *
 *********************************************/

// Define o título DESTA página.
$page_title = "";

// Opção ativa no menu
$page_menu = "edit";

// Inclui o cabeçalho da página
require_once $_SERVER['DOCUMENT_ROOT'] . "/_header.php";

?>

<?php // Conteúdo 
?>
<article>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <h2>Editar perfil</h2>

    <?php echo $form['feedback']; ?>

    <?php if ($show_form) : ?>

        <p>Altere os dados no formulário abaixo:</p>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" name="edit-profile">

            <input type="hidden" name="send-profile" value="true">

            <p>
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" placeholder="Seu nome completo." value="<?php echo $form['user_name'] ?>" autofocus>
            </p>

            <p>
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="Seu e-mail principal." value="<?php echo $form['user_email'] ?>" disabled>
                <small>O e-mail não pode ser alterado.</small>
            </p>

            <p>
                <label for="birth">Nascimento:</label>
                <input type="date" name="birth" id="birth" placeholder="Sua data de nascimento" value="<?php echo $form['user_birth'] ?>">
            </p>

            <p>
                <label for="password">Senha atual:</label>
                <input type="password" name="password" id="password" placeholder="Sua senha." value="">
            </p>

            <p>
                <label></label>
                <button type="submit">Salvar</button>
            </p>

        </form>

    <?php endif; ?>

</article>

<?php // Barra lateral 
?>
<aside>

    <h3>Seções:</h3>

    <ul>
        <li><a href="/sections/front.php">Front-end</a></li>
        <li><a href="/sections/back.php">Back-end</a></li>
        <li><a href="/sections/full.php">Full-stack</a></li>
    </ul>

</aside>

<?php

// Inclui o rodapé da página
require_once $_SERVER['DOCUMENT_ROOT'] . "/_footer.php";

?>