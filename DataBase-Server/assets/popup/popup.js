export default class Popup {
  constructor(title = "", content = "", footerBtn = {}) {
    this._popup = null;
    this._title = title;
    this._content = content;
    this.footerBtn = footerBtn;
    this._closingBtn = false;
  }

  async create() {
    await fetch('/assets/popup/popup.html').then(response => response.text())
      .then(data => {
        const template = document.createElement('template');
        template.innerHTML = data.trim();
        this.popup = template.content.firstChild;

        const title = this.popup.querySelector("#popup-title");
        title.innerHTML = this.title;

        if (this.closingBtn) {
          const header = this.popup.querySelector("#popup-header");
          const closeBtn = document.createElement("button");
          closeBtn.id = "close-popup";
          closeBtn.className = "fas fa-circle-xmark";
          closeBtn.onclick = () => {this.close()};
          header.appendChild(closeBtn);
        }

        const content = this.popup.querySelector("#popup-content");
        content.innerHTML = this.content;

        const footer = this.popup.querySelector("#popup-footer");
        for (const btnFunc in this.footerBtn) {
          let button = document.createElement("button");
          button.textContent = btnFunc;
          button.classList.add("btn");
          button.addEventListener("click", this.footerBtn[btnFunc]);
          footer.appendChild(button);
        }
      });
  }

  close() {
    if (this.popup)
      document.body.removeChild(this.popup);
  }

  async show() {
    this.create().then(() => {
      document.body.appendChild(this.popup)
      this.executeScripts(this.popup);
    });

  }

  executeScripts(element) {
    const scripts = element.querySelectorAll('script');
    scripts.forEach(script => {
      const newScript = document.createElement('script');
      if (script.src) {
        newScript.src = script.src;
        newScript.async = false;  // Important pour maintenir l'ordre d'exécution
      } else {
        newScript.textContent = script.textContent;
      }
      document.head.appendChild(newScript).parentNode.removeChild(newScript);
    });
  }

  // Getters and Setters

  get popup() {
    return this._popup;
  }

  set popup(value) {
    this._popup = value;
  }

  get title() {
    return this._title;
  }

  set title(value) {
    this._title = value;
  }

  get content() {
    return this._content;
  }

  set content(value) {
    this._content = value;
  }

  get closingBtn() {
    return this._closingBtn;
  }

  set closingBtn(value) {
    this._closingBtn = value;
  }

  addFooterBtn(name, func) {
    this.footerBtn[name] = func;
  }
}