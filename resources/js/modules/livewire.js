export class LiveWireModule {
  manager = undefined;
  CacheRejs = undefined;
  CacheAlpine = undefined;
  CacheLivewire = undefined;
  init() {}
  loading() {
    let self = this;
    this.manager.on("byteplatform::trigger_after", (el) => {
      if (self.CacheRejs != undefined) {
        clearTimeout(self.CacheRejs);
      }
      self.CacheRejs = setTimeout(function () {
        self.CacheRejs = undefined;
        clearTimeout(self.CacheRejs);
        if (self.CacheLivewire == true) {
          window.Livewire?.rescan();
        } else {
          window.Livewire?.start();
        }
        self.CacheLivewire = true;

        if (!self.CacheAlpine) self.manager.$alpine?.start();
        self.CacheAlpine = true;
      }, 80);
    });
    this.manager.on("byteplatform::loaded", (el) => {
      if (!window.Livewire) return;
      window.byteplatformManager.doTrigger(el);
      window.addEventListener("byteplatform-close", ({ detail: { option } }) => {
        let { id, component } = option;
        if (id) {
          let liveCom = window.Livewire.find(id)?.__instance;
          if (liveCom?.el) {
            liveCom.el.__deleting = true;
            let parentModal = liveCom.el.closest(".modal");
            let liveModal = bootstrap?.Modal?.getInstance(
              parentModal ?? liveCom.el
            );
            if (liveModal) {
              liveModal.hide();
            } else {
              liveCom.el.remove();
            }
          }
        }
        if (component) {
          let arr = window.Livewire.getByName(component);
          if (arr) {
            arr.forEach((item) => {
              let liveCom = item.__instance ?? item;
              if (liveCom?.el) {
                liveCom.el.__deleting = true;
                let liveModal = bootstrap?.Modal?.getInstance(
                  liveCom.__instance.el
                );
                if (liveModal) {
                  liveModal.hide();
                } else {
                  liveCom.el.remove();
                }
              }
            });
          }
        }
      });
      const LivewireRefreshData = (id) => {
        try {
          window.Livewire.dispatch("refreshData" + id);
        } catch (ex) {}
      };
      window.addEventListener("byteplatform-refresh", ({ detail: { option } }) => {
        let { module, id, component } = option;
        if (module) {
          LivewireRefreshData(module);
        }
        if (component) {
          if (!Array.isArray(component)) {
            component = [component];
          }
          component.forEach((element) => {
            LivewireRefreshData(element);
          });
        }
        if (id) {
          LivewireRefreshData(id);
        }
      });
      window.addEventListener("byteplatform-message", ({ detail: { option } }) => {
        if (typeof option === "string") {
          window.byteplatformManager.addInfo(option, "byteplatform-message");
        } else {
          const { error, message, type, meta, ...data } = option;
          window.byteplatformManager.addMessage(
            message ?? error,
            type,
            "byteplatform-message",
            { ...data, ...meta }
          );
        }
      });
    });
  }
  unint() {}
}
