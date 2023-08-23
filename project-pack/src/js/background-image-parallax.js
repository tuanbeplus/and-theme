export default class PP_BG_Parallax {
  
  constructor(elem, options) {
    this.elem = elem;
    this.options = {
      ...{
        transition: 'cubic-bezier(0,0,0,1)'
      },
      ...options
    };

    let screenHeight = window.innerHeight;
    let elTop = 0;
    let elHeight = 0;

    this.ratio = this.elem.dataset.ratio || 0.1;
    this.elem.style.transition = `${ this.options.transition }`;
    this.elem.style.backgroundPosition = `center top`;
    this.elem.style.backgroundRepeatY = `repeat`;

    const isElemInScreen = () => {
      let { top, height } = self.elem.getBoundingClientRect();
      elTop = top;
      elHeight = height;
      screenHeight = window.innerHeight;
      return (elTop - screenHeight) > 0 ? false : true;
    }

    const self = this;
    const onScroll = function(event) {
      if(! isElemInScreen()) return;
      let pos = ((elTop - screenHeight) * -1) * self.ratio;
      self.elem.style.backgroundPosition = `center ${ pos * -1 }px`;
    }

    window.addEventListener('scroll', onScroll);
  }
}