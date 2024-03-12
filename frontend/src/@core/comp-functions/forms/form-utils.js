export const useInputImageRenderer = (inputEl, callback) => {
  const inputImageRenderer = () => {
    let file = inputEl.value.files[0]
    
    var multiple = false;
    const reader = new FileReader()
    
    reader.addEventListener(
      'load',
      function(){
        if( typeof callback !== 'function' ) {
          callback.value.src = reader.result;
        } else {
          callback(reader.result)
        }
      },
      false,
    )

    if (file) {
      if( !inputEl.multiple ){
        reader.readAsDataURL(file)
      } 
    }
  }
  return {
    inputImageRenderer,
  }
}

export const _ = null
