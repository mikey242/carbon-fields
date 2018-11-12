/**
 * External dependencies.
 */
import cx from 'classnames';
import { Component } from '@wordpress/element';

class ComplexTabs extends Component {
	/**
	 * Renders the component.
	 *
	 * @return {Object}
	 */
	render() {
		const {
			current,
			groups,
			children,
			onChange
		} = this.props;

		return (
			<div className="cf-metaboxes-complex__tabs">
				<ul className="cf-metaboxes-complex__tabs-list">
					{ groups.map( ( group, index ) => {
						const classes = cx(
							'cf-metaboxes-complex__tabs-item',
							{
								'cf-metaboxes-complex__tabs-item--current': group.id === current
							}
						);

						return (
							<li
								key={ group.id }
								className={ classes }
								onClick={ () => onChange( group.id ) }
							>
								{
									group.label
										? <span
											className="cf-metaboxes-complex__tabs-title"
											dangerouslySetInnerHTML={ { __html: group.label } }
										></span>
										: null
								}

								<span className="cf-metaboxes-complex__tabs-index">
									{ index + 1 }
								</span>
							</li>
						);
					} ) }
				</ul>

				{ children }
			</div>
		);
	}
}

export default ComplexTabs;
