// SVG
const svg_success = `<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"></path></svg>`;
const svg_error = `<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10.425 1.414a3.33 3.33 0 0 1 3.026 -.097l.19 .097l6.775 3.995l.096 .063l.092 .077l.107 .075a3.224 3.224 0 0 1 1.266 2.188l.018 .202l.005 .204v7.284c0 1.106 -.57 2.129 -1.454 2.693l-.17 .1l-6.803 4.302c-.918 .504 -2.019 .535 -3.004 .068l-.196 -.1l-6.695 -4.237a3.225 3.225 0 0 1 -1.671 -2.619l-.007 -.207v-7.285c0 -1.106 .57 -2.128 1.476 -2.705l6.95 -4.098zm1.585 13.586l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" stroke-width="0" fill="currentColor"></path></svg>`;
const svg_warning = `<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M236.8,188.09,149.35,36.22h0a24.76,24.76,0,0,0-42.7,0L19.2,188.09a23.51,23.51,0,0,0,0,23.72A24.35,24.35,0,0,0,40.55,224h174.9a24.35,24.35,0,0,0,21.33-12.19A23.51,23.51,0,0,0,236.8,188.09ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"></path></svg>`;
const svg_information = `<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"></path></svg>`;

// MESSAGE WITH TYPE
function MessageType(title, message, info, type) {
    // türü yoksa başarısız dönsün
    if((type < 0 || type == null || type > 4))
        return false;

    // ana eleman kontrol
    var element_message_popup_area = null;

    if(document.querySelector("[popup='system-message']") === null) {
        element_message_popup_area = document.createElement("div");
        element_message_popup_area.setAttribute("popup", "system-message");
    }
    else {
        element_message_popup_area = document.querySelector("[popup='system-message']");
    }

    // eğer element hala yoksa false dönsün
    if(element_message_popup_area === null)
        return false;

    // eleman oluştursun
    const element_message = document.createElement("div");
    const element_message_text_area = document.createElement("div");
    const element_message_text_title_msg = document.createElement("h2");
    const element_message_text_msg = document.createElement("p");
    const element_message_text_info = document.createElement("p");

    // öznitelik
    element_message.setAttribute("id", "message-popup");
    element_message.setAttribute("class", "message-popup");

    element_message_text_area.setAttribute("id", "message-popup-text-area");
    element_message_text_area.setAttribute("class", "message-popup-text-area");

    element_message_text_title_msg.setAttribute("id", "message-popup-text-title");
    element_message_text_title_msg.setAttribute("class", "message-popup-text-title");

    element_message_text_msg.setAttribute("id", "message-popup-text-msg");
    element_message_text_msg.setAttribute("class", "message-popup-text-msg");

    element_message_text_info.setAttribute("id", "message-popup-text-info");
    element_message_text_info.setAttribute("class", "message-popup-text-info");

    // mesaj metini ayarlama
    element_message_text_title_msg.innerHTML = `<span style="margin: 0 0.5rem 0 0;">${title}</span>`;

    element_message_text_msg.innerText = (message).toString();
    element_message_text_info.innerText = (info).toString();

    // genel stil
    element_message_popup_area.style.cssText = `
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        position: fixed;
        diplay: flex;
        align-items: center;
        justify-content: center;
        top: 5vh;
        left: 50%;
        transform: translateX(-50%);
        z-index: 999;
        transition: 0.2s linear;
        background: transparent;
    `;

    element_message.style.cssText = `
        width: auto;
        height: auto;
        position: relative;
        display: block;
        align-items: center;
        justify-content: center;
        text-align: center;
        flex-direction: column;
        background: "transparent";
        font-family: sans-serif;
        border: none;
        border-radius: 1rem;
        padding: clamp(0.75rem, 5vh, 1.25rem) clamp(1rem, 5%, 1.75rem);
        margin: clamp(0.25rem, 5vh, 0.5rem) clamp(0.25rem, 5%, 0.5rem);
        opacity: 1;
        transition: 1s ease-in-out;
    `;

    element_message_text_area.style.cssText = `
        font-family: sans-serif;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        text-align: center;
    `;

    element_message_text_title_msg.style.cssText = `
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(1rem, 3vw, 1.5rem);
    `;

    element_message_text_msg.style.cssText = `
        font-size: clamp(0.7rem, 2vw, 0.8rem);
    `;

    element_message_text_info.style.cssText = `
        color: #ffffff;
        font-size: clamp(0.6rem, 2vw, 0.7rem);
        opacity: 0.75;
        margin: 0.125rem 0;
    `;
    
    // türüne göre küçük yazı, icon, durum(status)... ayarlama
    switch(type) {
        case 1: // başarı (success)
            element_message.setAttribute("data-status", "success");
            element_message_text_title_msg.innerHTML += `${svg_success}`;

            // başarıya ait özel stil
            element_message.style.color = "#ffffff";
            element_message.style.background = "#00de16";
            element_message.style.boxShadow = "0 0 30px 0px rgba(0,222,22,0.5)";
        break;
        case 2: // hata (error)
            element_message.setAttribute("data-status", "error");
            element_message_text_title_msg.innerHTML += `${svg_error}`;

            // hataya ait özel stil
            element_message.style.color = "#ffffff";
            element_message.style.background = "#ff1919";
            element_message.style.boxShadow = "0 0 30px 0px rgba(255,25,25,0.5)";
        break;
        case 3: // uyarı (warning)
        element_message.setAttribute("data-status", "warning");
            element_message_text_title_msg.innerHTML += `${svg_warning}`;

            // uyarıya ait özel stil
            element_message.style.color = "#ffffff";
            element_message.style.background = "#ffb113";
            element_message.style.boxShadow = "0 0 30px 0px rgba(255,177,19,0.5)";
        break;
        case 4: // bilgilendirme (information)
        element_message.setAttribute("data-status", "information");
            element_message_text_title_msg.innerHTML += `${svg_information}`;

            // bilgilendirmeye ait özel stil
            element_message.style.color = "#ffffff";
            element_message.style.background = "#0081ff";
            element_message.style.boxShadow = "0 0 30px 0px rgba(0,129,255,0.5)";
        break;
    }

    document.body.appendChild(element_message_popup_area);
    element_message_popup_area.appendChild(element_message);
    element_message.appendChild(element_message_text_area);
    element_message_text_area.appendChild(element_message_text_title_msg);
    element_message_text_area.appendChild(element_message_text_msg);
    element_message_text_area.appendChild(element_message_text_info);

    // süre sonunda görünmez yapsın
    setTimeout(function() {
        // görünürlüğünü sıfırla
        element_message.style.opacity = 0;

        // önceki zamanlayıcı bittikten 1.25 sn sonra silsin
        setTimeout(function() {
            element_message.remove();
        }, 1250);
    }, 3600);
}
