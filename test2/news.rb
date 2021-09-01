#
# IMPORTANT!
# Keep the list sorted alphabetically by topic name!

default['kafka']['company_db_topics'] = {
  'topic5' => { cleanup_policy: 'compact', partitions: 12, retention: '24h' },
  'topic1' => {},
}

default['kafka']['_topics'] = node['kafka']['xxx'] if node['kafka']['cluster_name'] == 'xxx'