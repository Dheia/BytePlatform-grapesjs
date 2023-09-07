export class LiveWireChartModule {
  manager = undefined;
  init() {}
  loading() {
    if (window.Livewire) {
      const self = this;
      window.Livewire.directive(
        "apexcharts",
        ({ el, directive, component, cleanup }) => {
          // Only fire this handler on the "root" directive.
          if (directive.modifiers.length > 0 || el.livewire____apexcharts) {
            return;
          }
          let options = {};
          if (el.hasAttribute("wire:apexcharts")) {
            options = new Function(
              `return ${el.getAttribute("wire:apexcharts")};`
            )();
          }
          if (el.livewire____apexcharts) {
            el.livewire____apexcharts.updateOptions(options);
            return;
          }
          const apexchartsInit = () => {
            if (el.livewire____apexcharts) return;
            el.livewire____apexcharts = new window.ApexCharts(el, options);
            el.livewire____apexcharts.render();
          };
          if (window.ApexCharts) {
            apexchartsInit();
          } else {
            window.addStyleToWindow(
              self.manager.getUrlPublic(
                "byteplatform/modules/byteplatform/apexcharts/dist/apexcharts.css"
              ),
              function () {}
            );
            window.addScriptToWindow(
              self.manager.getUrlPublic(
                "byteplatform/modules/byteplatform/apexcharts/dist/apexcharts.min.js"
              ),
              function () {
                apexchartsInit();
              }
            );
          }
        }
      );
    }
  }
  unint() {}
}
