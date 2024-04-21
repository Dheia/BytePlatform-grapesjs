import { SokeioPlugin } from "../core/plugin";

export class LiveWireFlatpickrModule extends SokeioPlugin {
  getKey() {
    return "SOKEIO_LIVEWIRE_FLATPICK_MODULE";
  }
  booting() {
    if (window.Livewire) {
      let self = this;
      window.Livewire.directive("flatpickr", ({ el, directive, component }) => {
        // Only fire this handler on the "root" directive.
        if (directive.modifiers.length > 0 || el.$wire_flatpickr) {
          return;
        }
        let options = {};

        if (el.hasAttribute("wire:flatpickr.options")) {
          options = new Function(
            `return ${el.getAttribute("wire:flatpickr.options")};`
          )();
        }
        let modelKey = el.getAttribute("wire:model");
        const flatpickrCreate = async () => {
          if (el.$wire_flatpickr) return;
          el.$wire_flatpickr = new window.flatpickr(el, {
            allowInput: true,
            allowInvalidPreload: true,
            dateFormat: "d/m/Y",
            ...options,
            onChange: (selectedDates, dateStr, instance) => {
              self
                .getManager()
                .dataSet(component.$wire, modelKey, selectedDates);
            },
          });
          // setTimeout(async () => {
          //   el.$wire_flatpickr.setDate(
          //     await self.getManager().dataGet(component.$wire, modelKey)
          //   );
          // }, 500);
        };
        window.addStyleToWindow(
          self
            .getManager()
            .getUrlPublic(
              "platform/modules/sokeio/flatpickr/dist/flatpickr.min.css"
            ),
          function () {}
        );
        if (window.flatpickr) {
          flatpickrCreate();
        } else {
          window.addScriptToWindow(
            self
              .getManager()
              .getUrlPublic(
                "platform/modules/sokeio/flatpickr/dist/flatpickr.min.js"
              ),
            function () {
              flatpickrCreate();
            }
          );
        }
      });
    }
  }
}
