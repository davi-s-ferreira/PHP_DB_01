<hr>

<h2>Alterar senha</h2>

<p>Para alterar sua senha, insira os dados abaixo:</p>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" name="edit-password">

    <input type="hidden" name="send-password" value="true">

    <p>
        <label for="password">Senha atual:</label>
        <input type="password" name="password" id="password" placeholder="Sua senha." value="">
    </p>

    <p>
        <label for="password1">Senha:</label>
        <input type="password" name="password1" id="password1" placeholder="Sua nova senha." value="">
        <small>Sua senha deve ter pelo menos 7 caracteres, uma letra maiúscula, uma minúscula e um número.</small>
    </p>

    <p>
        <label for="password2">Senha:</label>
        <input type="password" name="password2" id="password2" placeholder="Repita a senha." value="">
    </p>

    <p>
        <label></label>
        <button type="submit">Salvar</button>
    </p>

</form>