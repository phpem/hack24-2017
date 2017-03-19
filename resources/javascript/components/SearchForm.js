class SearchForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            topic: "",
            username: ""
        };

        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleInputChange(event) {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;

        this.setState({
            [name]: value
        });
    }

    handleSubmit(event) {
        alert('A search was submitted: ' + this.state.topic + ' ' + this.state.username);
        event.preventDefault();
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                How does&nbsp;
                <input type="text" className="main-search__input" name="username" value={this.state.username} placeholder="@username" onChange={this.handleInputChange} />&nbsp;
                feel about&nbsp;
                <input type="text" className="main-search__input" name="topic" value={this.state.topic} placeholder="topic" onChange={this.handleInputChange} />&nbsp;
                <input type="submit" className="main-search__button" value="?" />
            </form>
        );
    }
}

export default SearchForm;
