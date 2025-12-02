const Swal2 = Swal.mixin({
  customClass: {
    input: 'form-control'
  }
})

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})


// Safe binder utility
const bindClick = (id, handler) => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('click', handler);
};

// Demo triggers (guarded)
bindClick("basic", () => { Swal2.fire("Any fool can use a computer") });
bindClick("footer", () => { Swal2.fire({ icon: "error", title: "Oops...", text: "Something went wrong!", footer: "<a href>Why do I have this issue?</a>" }) });
bindClick("title", () => { Swal2.fire("The Internet?", "That thing is still around?", "question") });
bindClick("success", () => { Swal2.fire({ icon: "success", title: "Success" }) });
bindClick("error", () => { Swal2.fire({ icon: "error", title: "Error" }) });
bindClick("warning", () => { Swal2.fire({ icon: "warning", title: "Warning" }) });
bindClick("info", () => { Swal2.fire({ icon: "info", title: "Info" }) });
bindClick("question", () => { Swal2.fire({ icon: "question", title: "Question" }) });
bindClick("text", async () => { const { value: text } = await Swal2.fire({ input: "text", inputLabel: "Your IP address", showCancelButton: true, title: "Enter your IP address" }); if (text) Swal2.fire(text); });
bindClick("email", async () => { const { value: email } = await Swal2.fire({ title: "Input email address", input: "email", inputLabel: "Your email address", inputPlaceholder: "Enter your email address" }); if (email) Swal2.fire(`Entered email: ${email}`); });
bindClick("url", async () => { const { value: url } = await Swal2.fire({ input: "url", inputLabel: "URL address", inputPlaceholder: "Enter the URL" }); if (url) Swal2.fire(`Entered URL: ${url}`); });
bindClick("password", async () => { const { value: password } = await Swal2.fire({ title: "Enter your password", input: "password", inputLabel: "Password", inputPlaceholder: "Enter your password", inputAttributes: { maxlength: 10, autocapitalize: "off", autocorrect: "off" } }); if (password) Swal2.fire(`Entered password: ${password}`); });
bindClick("textarea", async () => { const { value: text } = await Swal2.fire({ input: "textarea", inputLabel: "Message", inputPlaceholder: "Type your message here...", inputAttributes: { "aria-label": "Type your message here" }, showCancelButton: true }); if (text) Swal2.fire(text); });
bindClick("select", async () => { const { value: fruit } = await Swal2.fire({ title: "Select field validation", input: "select", inputOptions: { Fruits: { apples: "Apples", bananas: "Bananas", grapes: "Grapes", oranges: "Oranges" }, Vegetables: { potato: "Potato", broccoli: "Broccoli", carrot: "Carrot" }, icecream: "Ice cream" }, inputPlaceholder: "Select a fruit", showCancelButton: true, inputValidator: (value) => new Promise((resolve) => { if (value === "oranges") resolve(); else resolve("You need to select oranges :)"); }) }); if (fruit) Swal2.fire(`You selected: ${fruit}`); });

// Toasts (guarded)
bindClick('toast-success', () => { Toast.fire({ icon: 'success', title: 'Signed in successfully' }) });
bindClick('toast-warning', () => { Toast.fire({ icon: 'warning', title: 'Please input your email' }) });
bindClick('toast-failed', () => { Toast.fire({ icon: 'error', title: 'Transaction error. Please try again later' }) });
