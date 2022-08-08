<template>
  <div class="vc-sketch-container" v-bind:class="vc_sketch_open" @blur.self="e => onBlur(e.relatedTarget)" tabindex="0">
    <div class="vc-sketch-box"  @click="togglePopover" :style="{background: activeColor}">
    </div>
    <transition name="vc-sketch-show-hide">
      <div v-show="isOpen" :class="['vc-sketch', disableAlpha ? 'vc-sketch__disable-alpha' : '']">
        <div class="vc-sketch-saturation-wrap">
          <saturation v-model="colors" @change="childChange"></saturation>
        </div>
        <div class="vc-sketch-controls">
          <div class="vc-sketch-sliders">
            <div class="vc-sketch-hue-wrap">
              <hue v-model="colors" @change="childChange"></hue>  
            </div>
            <div class="vc-sketch-alpha-wrap" v-if="!disableAlpha">
              <alpha v-model="colors" @change="childChange"></alpha>
            </div>
          </div>
          <div class="vc-sketch-color-wrap">
            <div class="vc-sketch-active-color" :style="{background: activeColor}"></div>
            <checkboard></checkboard>
          </div>
        </div>
        <div class="vc-sketch-field" v-if="!disableFields">
          <!-- rgba -->
          <div class="vc-sketch-field--double">
            <ed-in v-on:target_sketch="openSketch" label="hex" :value="hex" @change="inputChange"></ed-in>  
          </div>
          <div class="vc-sketch-field--single">
            <ed-in v-on:target_sketch="openSketch" label="r" :value="colors.rgba.r" @change="inputChange"></ed-in>
          </div>
          <div class="vc-sketch-field--single">
            <ed-in v-on:target_sketch="openSketch" label="g" :value="colors.rgba.g" @change="inputChange"></ed-in>
          </div>
          <div class="vc-sketch-field--single">
            <ed-in v-on:target_sketch="openSketch" label="b" :value="colors.rgba.b" @change="inputChange"></ed-in>
          </div>
          <div class="vc-sketch-field--single" v-if="!disableAlpha">
            <ed-in v-on:target_sketch="openSketch" label="a" :value="colors.a" :arrow-offset="0.01" :max="1" @change="inputChange"></ed-in>
          </div>
        </div>
        <div class="vc-sketch-presets">
          <div class="vc-sketch-presets-color"
            :aria-label="'color:'+c"
            v-for="c in presetColors"
            :key="c"
            :style="{background: c}"
            @click="handlePreset(c)">
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import colorMixin from '../mixin/color'
import editableInput from './common/EditableInput.vue'
import saturation from './common/Saturation.vue'
import hue from './common/Hue.vue'
import alpha from './common/Alpha.vue'
import checkboard from './common/Checkboard.vue'

const presetColors = [
  '#D0021B', '#F5A623', '#F8E71C', '#8B572A', '#7ED321',
  '#417505', '#BD10E0', '#9013FE', '#4A90E2', '#50E3C2',
  '#B8E986', '#000000', '#4A4A4A', '#9B9B9B', '#FFFFFF'
]

export default {
  name: 'Sketch',
  mixins: [colorMixin],
  components: {
    saturation,
    hue,
    alpha,
    'ed-in': editableInput,
    checkboard
  },
  props: {
    presetColors: {
      type: Array,
      default () {
        return presetColors
      }
    },
    disableAlpha: {
      type: Boolean,
      default: false
    },
    disableFields: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      internalIsOpen: false
    }
  },
  computed: {
    hex () {
      return this.colors.hex.replace('#', '')
    },
    activeColor () {
      var rgba = this.colors.rgba
      return 'rgba(' + [rgba.r, rgba.g, rgba.b, rgba.a].join(',') + ')'
    },
    isOpen () {
      if (this.inline) return false
      return this.internalIsOpen
    }
  },
  methods: {
    handlePreset (c) {
      this.colorChange({
        hex: c,
        source: 'hex'
      })
    },
    childChange (data) {
      this.colorChange(data)
    },
    inputChange (data) {
      // console.log('inputChange')
      if (!data) {
        return
      }
      if (data.hex) {
        this.isValidHex(data.hex) && this.colorChange({
          hex: data.hex,
          source: 'hex'
        })
      } else if (data.r || data.g || data.b || data.a) {
        this.colorChange({
          r: data.r || this.colors.rgba.r,
          g: data.g || this.colors.rgba.g,
          b: data.b || this.colors.rgba.b,
          a: data.a || this.colors.rgba.a,
          source: 'rgba'
        })
      }
    },
    hidePopover () {
      this.internalIsOpen = false
      this.$el.blur()
      this.vc_sketch_open = ''
      this.$emit('close', this.internalValue)
    },
    onBlur (relatedTarget) {
      /* istanbul ignore if */
      if (!this.isOpen) return /* dont hide */

      // We only close the Popover if the relatedTarget came from outside the component
      // Check if the relatedTarget is inside the component
      if (relatedTarget !== null && this.$el.contains(relatedTarget)) return /* dont hide */

      this.internalIsOpen = false
      this.vc_sketch_open = ''
      this.$emit('close', this.internalValue)
    },
    onFallbackButtonClick () {
      this.hidePopover()
    },
    showPopover () {
      /* istanbul ignore if */
      if (this.isOpen || this.inline || this.disabled) return /* dont show */

      this.internalIsOpen = true
      this.$el.focus()
      this.vc_sketch_open = 'vc-sketch-open'
      this.$emit('open')
    },
    togglePopover () {
      this.isOpen ? this.hidePopover() : this.showPopover()
      // console.log('this.isOpen', this.isOpen)
    },
    openSketch (value) {
      if (!value) {
        this.internalIsOpen = false
        this.vc_sketch_open = ''
        this.$emit('close', this.internalValue)
      }
    }
  }
}
</script>
<style>
.vc-sketch-container {
  display: inline-block;
  position: relative;
  width: 2rem;
  height: 2rem;
  border: 1px solid #dedede;
  box-shadow: 0 0 4px 2px rgba(0, 0, 0, 0.3);
  border-radius: 4px;
  z-index: 20;
}
.vc-sketch-container.vc-sketch-open {
  z-index: 21;
}
.vc-sketch-box {
  position: absolute;
  width: 100%;
  height: 100%;
  cursor: pointer;
  top: 0;
  left: 0;
  border-radius: 4px;
}
.vc-sketch {
  position: absolute;
  top: calc(100% + 1px);
  left: 0;
  z-index: 90;
  width: 200px;
  padding: 10px 10px 0;
  box-sizing: initial;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 0 0 1px rgba(0,0,0,.15), 0 8px 16px rgba(0,0,0,.15);
}
.vc-sketch-saturation-wrap {
  width: 100%;
  padding-bottom: 75%;
  position: relative;
  overflow: hidden;
}
.vc-sketch-controls {
  display: flex;
}
.vc-sketch-sliders {
  padding: 4px 0;
  flex: 1;
}
.vc-sketch-sliders .vc-hue,
.vc-sketch-sliders .vc-alpha-gradient {
  border-radius: 2px;
}
.vc-sketch-hue-wrap {
  position: relative;
  height: 10px;
}
.vc-sketch-alpha-wrap {
  position: relative;
  height: 10px;
  margin-top: 4px;
  overflow: hidden;
}
.vc-sketch-color-wrap {
  width: 24px;
  height: 24px;
  position: relative;
  margin-top: 4px;
  margin-left: 4px;
  border-radius: 3px;
}
.vc-sketch-active-color {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 2px;
  box-shadow: inset 0 0 0 1px rgba(0,0,0,.15), inset 0 0 4px rgba(0,0,0,.25);
  z-index: 2;
}
.vc-sketch-color-wrap .vc-checkerboard {
  background-size: auto;
}

.vc-sketch-field {
  display: flex;
  padding-top: 4px;
}
.vc-sketch-field .vc-input__input {
  width: 80%;
  padding: 4px 10% 3px;
  border: none;
  box-shadow: inset 0 0 0 1px #ccc;
  font-size: 11px;
}
.vc-sketch-field .vc-input__label {
  display: block;
  text-align: center;
  font-size: 11px;
  color: #222;
  padding-top: 3px;
  padding-bottom: 4px;
  text-transform: capitalize;
}
.vc-sketch-field--single {
  flex: 1;
  padding-left: 6px;
}
.vc-sketch-field--double {
  flex: 2;
}
.vc-sketch-presets {
  margin-right: -10px;
  margin-left: -10px;
  padding-left: 10px;
  padding-top: 10px;
  border-top: 1px solid #eee;
}
.vc-sketch-presets-color {
  border-radius: 3px;
  overflow: hidden;
  position: relative;
  display: inline-block;
  margin: 0 10px 10px 0;
  vertical-align: top;
  cursor: pointer;
  width: 16px;
  height: 16px;
  box-shadow: inset 0 0 0 1px rgba(0,0,0,.15);
}

.vc-sketch__disable-alpha .vc-sketch-color-wrap {
  height: 10px;
}

/* Transitions */
.vc-sketch-show-hide-enter-active, .vc-sketch-show-hide-leave-active {
  transition: all 0.3s ease;
}
.vc-sketch-show-hide-enter, .vc-sketch-show-hide-leave-active {
  opacity: 0;
}
</style>
