import React, {useState} from 'react';

export default function ({sendMessageUrl}) {
    const [message, setMessage] = useState('');

    const handleKeyDown = (event) => {
        if (event.key === 'Enter') {
            handleSendMessage();
        }
    }

    const handleSendMessage = (msg) => {
        if (message === '') {
            return;
        }

        fetch(sendMessageUrl, {
            method: 'POST',
            body: JSON.stringify({content: message})
        })
            .then(() => setMessage(() => ('')));
    }

    return <>
        <input
            type="text"
            className={'form-control'}
            value={message}
            onKeyDown={handleKeyDown}
            onInput={e => setMessage(e.target.value)}
        />
        <button className={'btn btn-primary'} onClick={handleSendMessage}>Send</button>
    </>
}
