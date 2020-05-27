import React from 'react';
import ReactDOM from 'react-dom';

function AddCompany() {

    const nameRef = React.createRef();

    const onSubmit = async (e) => {
        e.preventDefault();
        const name = nameRef.current.value;
        let uri = 'http://13.90.214.197:8000' + '/api/Company/AddCompany';

        let response = await axios.post(uri, {name});
        console.log(response);
        // Lets add company
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Add Company</div>
                        <div className="card-body">
                            <form onSubmit={onSubmit}>
                                <div className="form-group">
                                    <label >Company name</label>
                                    <input name="name" ref={nameRef} className="form-control" />
                                </div>
                                <button type="submit" className="btn btn-primary mb-2">Add </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default AddCompany;

if (document.getElementById('add-company')) {
    ReactDOM.render(<AddCompany />, document.getElementById('add-company'));
}
