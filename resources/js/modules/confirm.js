export class ConfirmModule {
  manager = undefined;
  clickEvent(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    let elCurrentTarget = e.target;
    let message = elCurrentTarget.getAttribute("byteplatform:confirm");
    let title = elCurrentTarget.getAttribute("byteplatform:confirm-title");
    let btnYes = elCurrentTarget.getAttribute("byteplatform:confirm-yes");
    let btnNo = elCurrentTarget.getAttribute("byteplatform:confirm-no");
    this.showConfirm(message, title, {
      btnYes,
      btnNo,
      onNo: function () {},
      onYes: function () {
        elCurrentTarget.dispatchEvent(
          new Event("click", {
            bubbles: true,
            cancelable: false,
          })
        );
      },
    });
  }

  init() {
    const self = this;
    this.manager.onDocument(
      "mousedown",
      "[byteplatform\\:confirm]",
      self.clickEvent.bind(self)
    );
    window.showConfirm = (content, title, option = undefined) => {
      self.showConfirm(content, title, option);
    };
  }
  loading() {}
  unint() {}
  showConfirm(content, title, option = undefined) {
    let { btnYes, btnNo, onYes, onNo } = option ?? {
      btnYes: undefined,
      btnNo: undefined,
      onYes: undefined,
      onNo: undefined,
    };
    const elConfirm = this.manager
      .htmlToElement(`<div class="modal " tabindex="-1">
                        <div class="modal-dialog modal-sm">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">${title}</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            ${content}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-no">${
                                  btnNo || "No"
                                }</button>
                              <button type="button" class="btn btn-primary btn-yes">${
                                btnYes || "Yes"
                              }</button>
                            </div>
                          </div>
                        </div>
                        </div>
                      `);

    const modalApp = bootstrap?.Modal?.getOrCreateInstance(elConfirm, {});
    modalApp.show();
    elConfirm.querySelector(".btn-no").addEventListener("click", function () {
      onNo && onNo();
      modalApp.hide();
    });
    elConfirm.querySelector(".btn-yes").addEventListener("click", function () {
      onYes && onYes();
      modalApp.hide();
    });
  }
}
