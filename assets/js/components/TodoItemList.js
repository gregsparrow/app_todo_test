import React, {Component} from 'react';
import axios from 'axios';
import {Button, Container, withStyles} from "@material-ui/core";
import CircularProgress from "@material-ui/core/CircularProgress";
import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";
import ListItemSecondaryAction from "@material-ui/core/ListItemSecondaryAction";
import Checkbox from "@material-ui/core/Checkbox";
import List from "@material-ui/core/List";
import IconButton from "@material-ui/core/IconButton";
import DeleteIcon from '@material-ui/icons/Delete';
import Grid from "@material-ui/core/Grid";
import Dialog from "@material-ui/core/Dialog";
import DialogTitle from "@material-ui/core/DialogTitle";
import DialogContent from "@material-ui/core/DialogContent";
import DialogActions from "@material-ui/core/DialogActions";
import {TextFields} from "@material-ui/icons";
import TextField from "@material-ui/core/TextField";
import Hidden from "@material-ui/core/Hidden";

const StyledCircularProgress = withStyles({
    root: {
        marginLeft: "auto",
        marginRight: "auto",
    }
})(CircularProgress);

const StyledList = withStyles((theme) => ({
    root: {
        width: '100%',
        backgroundColor: theme.palette.background.paper,
    }
}))(List);

class TodoItemList extends Component {
    constructor() {
        super();
        this.state = {items: [], loading: true, open: false, name: '', description: ''};
        this.handleToggle = this.handleToggle.bind(this);
        this.handleDelete = this.handleDelete.bind(this);
        this.handleCancel = this.handleCancel.bind(this);
        this.handleOk = this.handleOk.bind(this);
        this.handleAdd = this.handleAdd.bind(this);
    }

    componentDidMount() {
        this.getTodoItemList();
    }

    getTodoItemList() {
        this.setState((state) => {
            return {
                loading: true,
            }
        });
        axios.get('http://localhost:8080/api/todo_items').then(r => {
            this.setState({items: r.data})
        })
            .catch(reason => this.setState({loading: false}))
            .finally(() => this.setState({loading: false}))
    }

    deleteTodoItem(id) {
        this.setState((state) => {
            return {
                loading: true,
            }
        });
        axios.delete('http://localhost:8080/api/todo_items/' + id).then(r => {
            this.setState({items: r.data})
        })
            .catch(reason => this.setState({loading: false}))
            .finally(() => this.setState({loading: false}))
    }

    updateTodoItem(item) {
        this.setState((state) => {
            return {
                loading: true,
            }
        });
        axios.put('http://localhost:8080/api/todo_items/' + item.id, item).then(r => {
            this.setState({items: r.data})
        })
            .catch(reason => this.setState({loading: false}))
            .finally(() => this.setState({loading: false}))
    }

    addTodoItem(item)
    {
        this.setState((state) => {
            return {
                loading: true,
            }
        });
        axios.post('http://localhost:8080/api/todo_items', item).then(r => {
            this.setState({items: r.data})
        })
            .catch(reason => this.setState({loading: false}))
            .finally(() => this.setState({loading: false}));
    }

    handleToggle(item) {
        item.completed = !item.completed;
        this.updateTodoItem(item);
    }

    handleDelete(id) {
        this.deleteTodoItem(id);
    }

    open() {
        this.setState((state) => {
            return {
                open: !state.open,
            }
        });
    }

    handleCancel() {
        this.open();
    };

    handleOk() {
        this.open();
        let item = {
            name: this.state.name,
            description: this.state.description,
        }
        this.addTodoItem(item);
    };

    handleAdd() {
        this.open();
    }

    handleValueChange(e) {
        this.setState((state) => {
            return {
                [e.target.name]: e.target.value,
            }
        });
    }

    render() {
        const loading = this.state.loading;
        return (
            <Container maxWidth={"xs"} style={{marginTop: "20px"}}>
                {loading ? (
                    <CircularProgress/>
                ) : (
                    <Grid container spacing={3}>
                        <Grid item xs={12}>
                            <StyledList dense>
                                {this.state.items.map(item =>
                                    <ListItem key={item.id} button>
                                        <ListItemText id={item.id} primary={item.name} secondary={item.description}/>
                                        <ListItemSecondaryAction>
                                            <Checkbox
                                                edge="end"
                                                onChange={event => this.handleToggle(item)}
                                                checked={item.completed}
                                                inputProps={{'aria-labelledby': item.id}}
                                            />
                                            <IconButton edge="end" aria-label="delete" onClick={event => this.handleDelete(item.id)}>
                                                <DeleteIcon/>
                                            </IconButton>
                                        </ListItemSecondaryAction>
                                    </ListItem>
                                )}
                            </StyledList>
                        </Grid>
                        <Grid item xs={12} container justify={"flex-end"}>
                            <Button size="large" color={"primary"} variant={"contained"}
                                    onClick={event => this.handleAdd()}>Add</Button>
                        </Grid>
                    </Grid>
                )}

                <Dialog
                    disableBackdropClick
                    disableEscapeKeyDown
                    maxWidth="xs"
                    aria-labelledby="confirmation-dialog-title"
                    open={this.state.open}>
                    <DialogTitle id="confirmation-dialog-title">Add Todo</DialogTitle>
                    <DialogContent dividers>
                        <TextField label="Name" name="name" fullWidth onChange={event => this.handleValueChange(event)}/>
                        <TextField label="Description" name="description" fullWidth onChange={event => this.handleValueChange(event)}/>
                    </DialogContent>
                    <DialogActions>
                        <Button autoFocus onClick={event => this.handleCancel()} color="primary">
                            Cancel
                        </Button>
                        <Button onClick={event => this.handleOk()} color="primary">
                            Ok
                        </Button>
                    </DialogActions>
                </Dialog>

            </Container>
        )
    }
}

export default TodoItemList;
