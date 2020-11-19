import React, {Component} from 'react';
import {Route, Switch, Redirect, Link, withRouter} from 'react-router-dom';
import TodoItemList from "./TodoItemList";

class Home extends Component {

    render() {
        return (
            <Switch>
                <Redirect exact from="/" to="/todo_items"/>
                <Route path="/todo_items" component={TodoItemList}/>
            </Switch>
        )
    }
}

export default Home;
