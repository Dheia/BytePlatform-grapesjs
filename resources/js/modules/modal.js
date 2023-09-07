export class ModalModule {
  manager = undefined;
  getModalHtml($title, $size, $modalId) {
    return this.manager
      .htmlToElement(`<div class="modal modal-blur modal-byteplatform fade" id="${$modalId}" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered ${$size}" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">${$title}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <h1 class="text-center pb-5 modal-loading opacity-50">
            <div class="progress progress-sm mb-5">
              <div class="progress-bar progress-bar-indeterminate"></div>
            </div>
            Loading<span class="animated-dots"></span>
        </h1>
       
      </div>
    </div>
  </div>`);
  }
  openModal({ $url, $title, $size, $btnChoose, $modelField }, dataModal = {}) {
    let self = this;
    let elModal = self.getModalHtml(
      $title,
      $size,
      "modal-" + new Date().getTime()
    );
    const modalApp = window.bootstrap?.Modal?.getOrCreateInstance(elModal, {});
    modalApp.show();
    elModal.addEventListener("hidden.bs.modal", function (event) {
      elModal.__deleting = true;
      elModal.remove();
      window.Livewire?.rescan();
    });
    self.manager.$axios
      .post($url, dataModal, {
        timeout: 1000 * 10, // Wait for 10 seconds
        headers: {
          "Content-Type": "application/json",
        },
      })
      .then(({ data: htmlContent }) => {
        let $btnClass = "btn-choose-modal-" + new Date().getTime();
        if ($btnChoose != "") {
          htmlContent +=
            '<div class="p-2 text-center"><button class="btn btn-primary ' +
            $btnClass +
            '">' +
            $btnChoose +
            "</button></div>";
        }
        elModal
          .querySelector(".modal-loading")
          .setAttribute("style", "display: none;");
        elModal
          .querySelector(".modal-header")
          .insertAdjacentHTML("afterend", htmlContent);
        window.Livewire?.rescan();
        let modalWireComponent = elModal.querySelector("[wire\\:id]");
        if ($btnChoose != "" && modalWireComponent) {
          let modalWireId = modalWireComponent.getAttribute("wire:id");
          let refComponent = dataModal?.refComponent;
          elModal
            .querySelector("." + $btnClass)
            ?.addEventListener("click", async function () {
              let WireComponent = Livewire.find(modalWireId);
              let dataSelectItem = await WireComponent.getDataSelectItem();
              let WireComponentRef = Livewire.find(refComponent);
              if (WireComponentRef && $modelField) {
                self.manager.dataSet(
                  WireComponentRef,
                  $modelField,
                  self.manager.dataGet(WireComponent, "selectIds")
                );
              }
              modalApp.hide();
            });
        }
      })
      .catch(({ message }) => {
        setTimeout(() => {
          window.showToast(message, undefined, undefined, undefined, "error");
          modalApp.hide();
        }, 400);
      });
  }
  init() {
    let self = this;
    this.manager.onDocument("click", "[byteplatform\\:modal]", function (e) {
      let elCurrentTarget = e.target;
      let $url = elCurrentTarget.getAttribute("byteplatform:modal");
      let $modelField = elCurrentTarget.getAttribute("byteplatform:model");
      if (!$url) return;
      let $title = elCurrentTarget.getAttribute("byteplatform:modal-title") ?? "";
      let $size = elCurrentTarget.getAttribute("byteplatform:modal-size") ?? "";
      let $btnChoose = "";
      if (elCurrentTarget.hasAttribute("byteplatform:modal-choose")) {
        $btnChoose = elCurrentTarget.getAttribute("byteplatform:modal-choose");
        if ($btnChoose == "") $btnChoose = "Choose";
      }
      let parentEl = elCurrentTarget.closest("[wire\\:id]");
      let refComponent = parentEl?.getAttribute("wire:id");
      let selectIds = undefined;
      if ($btnChoose && $modelField) {
        selectIds = self.manager.dataGet(
          Livewire.find(refComponent),
          $modelField
        );
      }
      self.openModal(
        { $url, $title, $size, $btnChoose, $modelField },
        { refComponent, selectIds }
      );
    });
  }
  loading() {}
  unint() {}
}
