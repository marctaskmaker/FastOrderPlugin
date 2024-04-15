import Plugin from "src/plugin-system/plugin.class";
import HttpClient from "src/service/http-client.service";

export default class FastOrderPlugin extends Plugin {
  init() {
    this._client = new HttpClient();

    const form = document.getElementById("fast-order-form");
    if (form != null) {
      form.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
          event.preventDefault();
        }
      });
    }

    this.completionEvent();
    this.articleEvents();
    this.quantityEvents();
  }

  completionEvent() {
    const completion = document.querySelectorAll(".fast-order-completion");
    document.body.addEventListener("click", (event) => {
      for (let i = 0; i < completion.length; i++) {
        completion[i].innerHTML = "";
        completion[i].style.display = "none";
      }
    });
  }

  articleEvents() {
    const events = ["input", "focusin"];
    const inputArticle = document.querySelectorAll(".fast-order-input-article");
    for (let i = 0; i < inputArticle.length; i++) {
      for (let ev = 0; ev < events.length; ev++) {
        inputArticle[i].addEventListener(events[ev], (event) => {
          inputArticle[i].setAttribute("data-price", "");
          this.calculateTotal();

          const searchValue = event.target.value.trim();
          if (searchValue == "") {
            inputArticle[i].nextElementSibling.innerHTML = "";
            inputArticle[i].nextElementSibling.style.display = "none";
          } else {
            this._client.get(
              "/fast-order/articles/" + encodeURIComponent(searchValue),
              this.handleData.bind(this),
            );
          }
        });
      }
    }
  }

  quantityEvents() {
    const inputQuantity = document.querySelectorAll(
      ".fast-order-input-quantity",
    );
    for (let i = 0; i < inputQuantity.length; i++) {
      inputQuantity[i].addEventListener("input", (event) => {
        this.calculateTotal();
      });
    }
  }

  handleData(content) {
    const input = document.activeElement;
    input.nextElementSibling.innerHTML = content;
    input.nextElementSibling.style.display = "block";

    const links = document.querySelectorAll(".fast-order-link");
    for (let i = 0; i < links.length; i++) {
      links[i].addEventListener("click", (event) => {
        const dataId = links[i].getAttribute("data-id");
        const dataPrice = links[i].getAttribute("data-price");

        input.value = dataId;
        input.setAttribute("data-price", dataPrice);

        this.calculateTotal();
      });
    }
  }

  calculateTotal() {
    let total = 0;
    const inputArticle = document.querySelectorAll(".fast-order-input-article");
    const inputQuantity = document.querySelectorAll(
      ".fast-order-input-quantity",
    );

    const totalProductDisplay = document.querySelectorAll(
      ".fast-order-product-total-price",
    );

    for (let i = 0; i < inputArticle.length; i++) {
      if (
        inputArticle[i].getAttribute("data-price") &&
        inputQuantity[i].value.trim()
      ) {
        let quantity = parseInt(inputQuantity[i].value);
        let price = parseFloat(inputArticle[i].getAttribute("data-price"));
        let totalProduct = price * quantity;
        inputQuantity[i].nextElementSibling.style.display = "block";
        totalProductDisplay[i].innerHTML = totalProduct.toFixed(2);
        total = total + totalProduct;
      } else {
        inputQuantity[i].nextElementSibling.style.display = "none";
        totalProductDisplay[i].innerHTML = "";
      }
    }

    const displayTotal = document.getElementById("totalPrice");
    displayTotal.innerHTML = total.toFixed(2);
  }
}
