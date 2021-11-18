/* This example requires Tailwind CSS v2.0+ */
import React, {Fragment, useRef, useState} from 'react'
import {Form} from "./form";
import ReactDOM from "react-dom";

export function PostForm() {
    const [open, setOpen] = useState(false)
    const [data, setData] = useState(null);

    React.useEffect(() => {
        window.$('.post-form').on('click', (e) => {
            setData(JSON.parse(e.target.dataset.json))
            setOpen(true)
        });

        window.$('.post-form-delete').on('click', (e) => {
            const data = JSON.parse(e.target.dataset.json)
            if (confirm('Вы действительно хотите удалить пост?')) {
                axios.delete(`/admin/posts/${data?.id}`)
            }
        });

    }, [])
    const handleChange = (e) => {
        setData({
            ...data,
            [e.target.name]: e.target.value
        })
    }

    const handleClick = async () => {
        await axios.put(`/admin/posts/${data.id}`, data);
        setOpen(false);
    }

    return (
        <Form setOpen={setOpen} open={open} data={data} handleClick={handleClick}>
            <form action="#" method="POST">
                <div className="shadow overflow-hidden sm:rounded-md">
                    <div className="px-4 py-5 bg-white sm:p-6">
                        <div className="grid grid-cols-6 gap-6">
                            <div className="col-span-6 sm:col-span-3">
                                <label htmlFor="first-name"
                                       className="block text-sm font-medium text-gray-700">
                                    Название
                                </label>
                                <input
                                    defaultValue={data?.title}
                                    onChange={handleChange}
                                    type="text"
                                    name="name"
                                    id="name"
                                    autoComplete="given-name"
                                    className="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>

                            <div className="col-span-6 sm:col-span-3">
                                <label htmlFor="country" className="block text-sm font-medium text-gray-700">
                                    Статус
                                </label>
                                <select
                                    onChange={handleChange}
                                    defaultValue={data?.status}
                                    id="status"
                                    name="status"
                                    autoComplete="status"
                                    className="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    {window?.statuses?.map(status => {
                                        return (
                                            <option value={status} key={status}>{status}</option>
                                        )
                                    })}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </Form>
    )
}

if ($('.post-form').length && document.getElementById('react-form')) {
    ReactDOM.render(<PostForm/>, document.getElementById('react-form'));
}
