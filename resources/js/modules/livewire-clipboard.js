export class LiveWireFlatpickrModule {
  manager = undefined;
  init() {}
  loading() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("clipboard", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.livewire____clipboard) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:clipboard.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:clipboard.options")};`
          )();
        }
        const clipboardCreate = () => {
          if (el.livewire____clipboard) return;
          el.livewire____clipboard = new window.Clipboard(el, options);
        };
        if (window.Clipboard) {
          clipboardCreate();
        } else {
          window.addScriptToWindow(
            self.manager.getUrlPublic(
              "byteplatform/modules/byteplatform/clipboard/dist/clipboard.min.js"
            ),
            function () {
              clipboardCreate();
            }
          );
        }
      });
    }
  }
  unint() {}
}
