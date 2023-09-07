export class ActionModule {
  manager = undefined;
  init() {}
  loading() {
    let selfManager = this;
    this.manager.onDocument("click", "[byteplatform\\:action]", function (e) {
      let elCurrentTarget = e.target;
      elCurrentTarget.setAttribute("byteplatform:action-loading", "");
      let action = elCurrentTarget.getAttribute("byteplatform:action");
      let url = elCurrentTarget.getAttribute("byteplatform:action-url");
      let method = elCurrentTarget.getAttribute("byteplatform:action-method");
      let body = elCurrentTarget.getAttribute("byteplatform:action-body");
      let success = elCurrentTarget.getAttribute("byteplatform:action-success");
      let error = elCurrentTarget.getAttribute("byteplatform:action-error");
      if (action && action != "") {
        //TODO: CALL TO ACTION
        self.manager.$axios
          .post(self.manager.getUrl("action"), {
            action,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("byteplatform:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("byteplatform:action-loading");
          });
      } else if (url && url != "") {
        selfManager.manager.$axios
          .request({
            method,
            url,
            data: body ?? {},
          })
          .then(() => {
            console.log({ success });
            elCurrentTarget.removeAttribute("byteplatform:action-loading");
          })
          .catch(() => {
            console.log({ error });
            elCurrentTarget.removeAttribute("byteplatform:action-loading");
          });
      }
    });
  }
  unint() {}
}
