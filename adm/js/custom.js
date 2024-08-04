//-------------------------FUNÇÃO PESQUISAR ALUNO ------------------------------------


//Receber o seletor form-pesquisar
const formPesquisar = document.getElementById("form-pesquisar");

//Verificar se existe o form-pesquisar
if(formPesquisar){
  //Aguardar o submit, quando o usuário clicar no botão executa a função 
  formPesquisar.addEventListener("submit", async (e) => {

    //Bloquear o carregamento da página
    e.preventDefault();

    // Receber os dados do formulário
    const dadosForm = new FormData(formPesquisar);

    //Imporimir o valor que vem do formulário
    /*for( var dadosFormPesq of dadosForm.entries()){
      console.log(dadosFormPesq[0] + " - " + dadosFormPesq[1]);
    }*/

    const dados = await fetch("pesquisar",{
      method:"POST",
      body: dadosForm
    });

    const resposta = await dados.json();
    console.log(resposta);

    if (!resposta['status']) {
      document.getElementById("msg").innerHTML = resposta['msg'];
    }else{

    }
  });
}

//-------------------------FIM DA FUNÇÃO PESQUISAR ALUNO------------------------------------

//---------------------------- Apagar mensagem de sucesso ------------------

function removeMensagem(){
    setTimeout(function(){ 
        var msg = document.getElementById("msg-success");
        msg.parentNode.removeChild(msg);   
    }, 5000);
}
document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
      // toda vez que a página carregar, vai limpar a mensagem (se houver) 
      // após 5 segundos
        removeMensagem(); 
    }
};

