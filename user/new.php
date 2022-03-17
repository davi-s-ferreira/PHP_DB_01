<?php

// Inclui arquivo de configuração
require_once $_SERVER['DOCUMENT_ROOT'] . "/_config.php";

/*******************************************
 * Seu código PHP desta página entra aqui! *
 *******************************************/

// Variáveis desta página
$form = [
    'user_name' => '',
    'user_email' => '',
    'user_birth' => '',
    'password' => '',
    'password2' => '',
    'feedback' => ''
];

// Variável que exibe/oculta formulário.
$show_form = true;

// Detecta se o formulário foi enviado...
if (isset($_POST['send'])) :

    // Obtém os valores dos campos, sanitiza e armazena nas variáveis.
    // Atenção! A função "sanitize()" está em "/_config.php".
    $form['user_name'] = sanitize('name', 'string');
    $form['user_email'] = sanitize('email', 'email');
    $form['user_birth'] = sanitize('birth', 'string');
    $form['password'] = sanitize('password', 'string');
    $form['password2'] = sanitize('password2', 'string');

    // Verifica se todos os campos form preenchidos
    if ($form['user_name'] === '' or $form['user_email'] === '' or $form['user_birth'] === '' or $form['password'] === '' or $form['password2'] === '') :
        $form['feedback'] = '<h3 style="color:red">Erro: por favor, preencha todos os campos!</h3>';

    // Verifica se as senhas digitadas coincidem
    elseif ($form['password'] !== $form['password2']) :
        $form['feedback'] = '<h3 style="color:red">Erro: as senhas não coincidem!</h3>';
        $form['password'] = $form['password2'] = '';

    // Verifica se a data é válida
    elseif (!validate_date($form['user_birth'])) :
        $form['feedback'] = '<h3 style="color:red">Erro: a data de nascimento está incorreta!</h3>';
        $form['user_birth'] = '';
    else :

        // Cria a query para salvar no banco de dados.
        $sql = <<<SQL

INSERT INTO users (
    user_name,
    user_email,
    user_birth,
    user_password
) VALUES 
(
    '{$form['user_name']}',
    '{$form['user_email']}',
    '{$form['user_birth']}',
    SHA2('{$form['password']}', 512)
);

SQL;

        // Salva contato no banco de dados.
        $conn->query($sql);

        // Obtém somente primeiro nome do rementente.
        $first_name = explode(" ", $form['user_name'])[0];

        // Cria mensagem de confirmação.
        $form['feedback'] = <<<OUT
        
    <h3>Olá {$first_name}!</h3>
    <p>Seu cadastro foi criado com sucesso.</p>
    <p>Você já pode acessar as áreas restritas do site quando se logar.</p>
    <p><em>Obrigado...</em></p>
    
OUT;

        // Oculto o formulário.
        $show_form = false;

        // Data de envio.
        $now = date('d/m/Y \à\s H:i');

        // Enviar e-mail para o administrador.
        $to = $site['admin'];
        $sj = 'Novo cadastro em ' . $site['name'] . '.';
        $msg = <<<MSG

Um novo usuário se cadastrou em {$site['name']}:

    Data: {$now}
    Nome: {$form['user_name']}
    E-mail: {$form['user_email']}
    Nascimento: {$form['user_birth']}

Obrigado...

MSG;
        @mail($to, $sj, $msg);

    endif;

endif; // if (isset($_POST['send']))

/*********************************************
 * Seu código PHP desta página termina aqui! *
 *********************************************/

// Define o título DESTA página.
$page_title = "";

// Opção ativa no menu
$page_menu = "new";

// Inclui o cabeçalho da página
require_once $_SERVER['DOCUMENT_ROOT'] . "/_header.php";

?>

<?php // Conteúdo 
?>
<article>

    <h2>Novo usuário</h2>

    <?php echo $form['feedback']; ?>

    <?php if ($show_form) : ?>

        <p>Preencha todos os campos do formulário para se cadastrar no <?php echo $site['name'] ?>.</p>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

            <input type="hidden" name="send" value="true">

            <p>
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" placeholder="Seu nome completo." value="<?php echo $form['user_name'] ?>" autofocus>
            </p>

            <p>
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" placeholder="Seu e-mail principal." value="<?php echo $form['user_email'] ?>">
            </p>

            <p>
                <label for="birth">Nascimento:</label>
                <input type="date" name="birth" id="birth" placeholder="Sua data de nascimento" value="<?php echo $form['user_birth'] ?>">
            </p>

            <p>
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" placeholder="Sua senha." value="">
                <small>Sua senha deve ter pelo menos 7 caracteres, uma letra maiúscula, uma minúscula e um número.</small>
            </p>

            <p>
                <label for="password2">Senha:</label>
                <input type="password" name="password2" id="password2" placeholder="Repita a senha." value="">
            </p>

            <p>
                <label></label>
                <button type="submit">Cadastrar</button>
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