// ======================== LOGIN ============================================

class login {
    constructor(idlogin) {
        this.container = document.getElementById(idlogin);

          this.container.querySelector('.inscription').addEventListener("click", () => {

            document.getElementById('inscriptionContent').style.display = 'block';
            document.getElementById('connectionContent').style.display = 'none';

        });
        this.container.querySelector(".connection").addEventListener("click", () => {
            document.getElementById('connectionContent').style.display = 'block';
            document.getElementById('inscriptionContent').style.display = 'none';
        });
    };
  };
let SwitchLogin = new login("loginBox");
