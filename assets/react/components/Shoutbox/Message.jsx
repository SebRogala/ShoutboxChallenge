import React from 'react';
import {Anchorme} from "react-anchorme";
import "./Message.css";

export default function ({message, fileAssetUri}) {
    let content;
    switch (message.type) {
        case 'text':
            content = <Anchorme target="_blank">{message.content}</Anchorme>;
            break;
        case 'file_uri':
            content = <img src={fileAssetUri + '/' + message.content}></img>;
            break;
    }

    return <>
        <div className={'message-container'} key={message.id}>
            <div className={'message-container__sender'}>
                <div>{message.userName}</div>
                <div>{message.createdAt}</div>
            </div>
            <div className={'message-container__content'}>
                {content}
            </div>
        </div>
    </>
}
