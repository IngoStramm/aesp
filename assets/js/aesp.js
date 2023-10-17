function setMask(cssClass, pattern, currency = false) {
    const nodeList = document.querySelectorAll(cssClass);
    for (let node of nodeList) {
        const maskOptions = {
            mask: pattern,
        };
        if (currency) {
            maskOptions.blocks = {
                num: {
                    mask: Number,
                    thousandsSeparator: '.',
                    scale: 2,
                    padFractionalZeros: true
                }
            };
        }
        const mask = IMask(node, maskOptions);
    }
}

function initMasks() {
    // CEP
    setMask('.cep', '00000{-}000');
    // CPF
    setMask('.cpf', '000{.}000{.}000{-}00');
    // Fone
    setMask('.fone', '00 [0]{0000-0000}');
    // Salario
    setMask('.salario', 'R$ num', true);
}

function callbackCep(conteudo) {
    if ('erro' in conteudo) {
        const cep = document.getElementById('cep');
        const msg = 'CEP não encontrado.';
        alert(msg);
        console.warn(msg);
        cep.value = '';
        cep.focus();
        return conteudo.errorMsg = msg;
    }
    return completaEndereco(conteudo);
}

function consultaCep(cepInput) {
    if (typeof cepInput === undefined || !cepInput) {
        return;
    }
    cepInput.addEventListener('blur', () => {
        let cep = cepInput.value;
        cep = cep.replace(/\D/g, '');
        if (cep === '' || cep.length < 8) {
            return false;
        }
        const validacep = /^[0-9]{8}$/;
        if (!validacep.test(cep)) {
            console.error('Formato de CEP inválido.');
            return {
                erro: true,
                errorMsg: 'Formato de CEP inválido.'
            };
        }
        const script = document.createElement('script');
        script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=callbackCep';
        document.body.appendChild(script);
    });
}

function completaEndereco(endereco) {
    const rua = document.getElementById('rua');
    rua.value = endereco.logradouro;
    const cidade = document.getElementById('cidade');
    cidade.value = endereco.localidade;
    const uf = document.getElementById('uf');
    uf.value = endereco.uf;
    const bairro = document.getElementById('bairro');
    bairro.value = endereco.bairro;
    const numero = document.getElementById('numero');
    numero.focus();
}

function initCep() {
    const cepInput = document.getElementById('cep');
    consultaCep(cepInput);
}

function ajax_curriculo_form() {
    const formCurriculo = document.getElementById('form-curriculo');

    if (typeof formCurriculo === formCurriculo || !formCurriculo) {
        return;
    }
    
    const submitBtn = formCurriculo.querySelector('button');

    if (submitBtn.disabled) {
        return;
    }

    formCurriculo.addEventListener('submit', e => {
        e.preventDefault();

        const formLoadWrapper = formCurriculo.querySelector('.form-load-wrapper');
        formLoadWrapper.style.display = 'block';
        // console.log('formLoadWrapper', formLoadWrapper);

        const successDiv = document.getElementById('success-message');
        if (typeof successDiv !== undefined && successDiv) {
            successDiv.remove();
        }

        const errorMessages = formCurriculo.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        const errorInputs = formCurriculo.querySelectorAll('.error');
        errorInputs.forEach(input => input.classList.remove('error'));

        submitBtn.disabled = true;
        const nome = document.getElementById('nome');
        const sobrenome = document.getElementById('sobrenome');
        const email = document.getElementById('email');
        const cep = document.getElementById('cep');
        const rua = document.getElementById('rua');
        const numero = document.getElementById('numero');
        const bairro = document.getElementById('bairro');
        const complemento = document.getElementById('complemento');
        const cidade = document.getElementById('cidade');
        const uf = document.getElementById('uf');
        const cpf = document.getElementById('cpf');
        const fone = document.getElementById('fone');
        const redeSocial = document.getElementById('rede-social');
        const cargo = document.getElementById('cargo');
        const formacao = document.getElementById('formacao');
        const outrosCursos = document.getElementById('outros-cursos');
        const experiencia = document.getElementById('experiencia');
        const salario = document.getElementById('salario');
        const info = document.getElementById('info');

        const formData = new FormData(formCurriculo);

        formData.append('action', 'aesp_post_curriculo_form');
        // console.log('formData', formData);

        // for (const [key, value] of formData) {
        //     console.log('formData', `${key}: ${value}`);
        // }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', ajax_object.ajax_url, true);
        xhr.onreadystatechange = function (res) {
            if (this.readyState === 4 && this.status === 200) {
                const res = JSON.parse(xhr.responseText);
                if (res.error_messages) {
                    const ul = document.createElement('ul');
                    ul.classList.add('error-messages');
                    for (const key in res.error_messages) {
                        const input = document.getElementById(key);
                        if (typeof input !== undefined && input) {
                            input.classList.add('error');
                        }
                        const li = document.createElement('li');
                        li.classList.add('error-message');
                        li.innerHTML = `<i class="fas fa-times"></i> ${res.error_messages[key]}`;
                        ul.append(li);
                    }
                    formCurriculo.append(ul);
                } else if (res.success) {
                    const successMsg = document.createElement('div');
                    successMsg.id = 'success-message';
                    successMsg.classList.add('success-message');
                    successMsg.innerHTML = `<i class="fas fa-check"></i> Formulário enviado com sucesso!`;
                    formCurriculo.append(successMsg);
                }
                // console.log(res);
            }
            if (this.readyState === 4 && this.status === 404) {
                console.log('An error occurred');
            }
            submitBtn.disabled = false;
            formLoadWrapper.style.display = 'none';

        };
        xhr.send(formData);

    });
}

document.addEventListener('DOMContentLoaded', function () {
    initMasks();
    initCep();
    ajax_curriculo_form();
}, false);