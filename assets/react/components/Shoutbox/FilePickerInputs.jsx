import React, {useState} from 'react';

export default function ({sendFileUrl}) {
    const [file, setFile] = useState(null)

    function handleChange(event) {
        setFile(event.target.files[0])
    }

    const handleSendFile = (ev) => {
        if (file === null) {
            return;
        }

        const formData = new FormData();
        formData.append("image", file);

        fetch(sendFileUrl, {
            method: 'POST',
            body: formData
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text)
                    })
                }
                setFile(() => null);
                ev.target.previousSibling.value = null
            })
            .catch(err => {
                alert(err);     //i know - handling errors in 90' manner, right?...
            })
        ;
    }

    return <div>
        <input
            type="file"
            className={'form-control'}
            onChange={handleChange}
        />
        <button className={'btn btn-primary'} onClick={handleSendFile}>Upload</button>
    </div>
}
